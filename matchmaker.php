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

	// $waitingUsers = multi_result_query("SELECT * FROM matchmaking WHERE userId != '$userId' ORDER BY request_time LIMIT 1 FOR UPDATE");
	$waitingUser = $matchmaker->getWaitingUser();

	if (!empty($waitingUser) && $waitingUser['userId'] != $userId) {
		// $waitingUser = current($waitingUsers);
		$matchmaker->deleteWaitingUser();

		$matchTime = time();
		$questions = result_array('trivia', '', 0, 2, ['RAND()' => '']);
		$matchToken = md5($waitingUser['userId'] . ' vs ' . $userId . ' at ' . $matchTime);

		$matchData = [
			'users' => [$waitingUser['userId'], $userId],
			'channels' => [$waitingUser['userChannel'], $currentUser['userChannel']],
			'questions' => $questions,
			'time' => time(),
		];

		$matchmaker->saveMatchData($matchToken, $matchData);

		echo json_encode(['success' => true, 'status' => 'matched']);

		exec('php ' . __DIR__ . '/cli/game.php ' . $matchToken . ' >> /tmp/game.log &');
		return;
	} else {
		// insert('matchmaking', $matchmakingData);

		$matchmaker->saveWaitingUser($userId, $currentUser['userChannel']);
		// exec(Fake enemy checker)
		echo json_encode(['success' => true, 'status' => 'waiting']);
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
}
