<?php
$begin = microtime(true);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db/db.php';
require_once __DIR__ . '/db/auth.php';

if (!$currentUser = getCurrentUser()) {

	header('HTTP/1.0 403 Forbidden');
	echo json_encode(['error' => true]);

} else {
	$start = microtime(true);
	$userId = $currentUser['sub'];
	$matchmaker = new Matchmaker();

	$matchToken = $_POST['gameId'];
	$matchData = $matchmaker->getMatchData($matchToken);

	if (!empty($matchData) && in_array($userId, $matchData['users'])) {
		list($base36time, $crcHash) = explode('.', $_POST['token']);
		$time = base_convert($base36time, 36, 10);
		$index = intval($_POST['index']);
		$currentTime = time();
                if (!empty($matchData['answers'][$index][$userId]) || crc32($time . 'Hello, Novita!' . $index) != $crcHash || $time < $currentTime - 10) {
                        header('HTTP/1.0 400 Bad Request');
                        echo json_encode(['error' => true]);
                        return;
                }

                $answer = $_POST['answer'];
                $correctAnswer = $matchData['questions'][$index]['answer'];
                $matchData['answers'][$index][$userId] = $answer;

                $score = 0;
                if ($answer == $correctAnswer) {
                        $score = 100 + $index * 10;
                        $timeDiff = $currentTime - $time;
                        if ($timeDiff > 6) {
                                $score -= 25;
                        } elseif($timeDiff > 8) {
                                $score -= 50;
                        }
                }

                $matchData['score'][$userId] = ($matchData['score'][$userId] ?: 0) + $score;
                $matchmaker->saveMatchData($matchToken, $matchData);
                $enemyUserId = $userId == $matchData['users'][0] ? $matchData['users'][0] : $matchData['users'][1];

		$matchmaker->saveMatchData($matchToken, $matchData);

		$data = [
			'success' => true,
			'correctAnswer' => $correctAnswer,
			'myAnswer' => $answer,
			'enemyHasAnswered' => count($matchData['answers'][$index]) > 1
		];
		echo json_encode($data);
	} else {
                header('HTTP/1.0 403 Forbidden');
                echo json_encode(['error' => true]);
	}
}

class Matchmaker
{
	const CACHE_KEY = 'matchmaker';
	const WAITING_TIME_SECOND = 100;
	private $memcached;

	public function __construct()
	{
		$this->memcached = new Memcached;
		$this->memcached->addServer('localhost', 11211);
	}

	public function getWaitingUser()
	{
		$result = $this->memcached->get(self::CACHE_KEY);
		return $result;
	}

	public function saveWaitingUser($userId, $userChannel)
	{
		$matchmakingData = [
			'userId' => $userId,
			'time' => time(),
			'userChannel' => $userChannel,
		];
		$result = $this->memcached->set(self::CACHE_KEY, $matchmakingData, self::WAITING_TIME_SECOND);
		return $result;
	}

	public function deleteWaitingUser()
	{
		$result = $this->memcached->delete(self::CACHE_KEY, 0);
		return $result;
	}

	public function saveMatchData($matchToken, $matchData)
	{
		$this->memcached->set('match_' . $matchToken, $matchData, 600);
	}

        public function getMatchData($matchToken)
        {
                return $this->memcached->get('match_' . $matchToken);
        }
}
