<?php

require_once dirname(__DIR__) . '/db/db.php';

$matchToken = $argv[1];
$game = new Game($matchToken);

$matchData = $game->getMatchData();
if (!empty($matchData)) {
	echo "Match data found: $matchToken\n";
	$game->sendToUsers(['type' => 'matched', 'users' => $matchData['usernames']]);
	sleep(5);

	var_dump($matchData);

	foreach ($matchData['questions'] as $index => $question) {
		echo "Sending question #$index\n";
		$publishQuestion = $game->buildQuestion($index, $question);
		$game->sendToUsers($publishQuestion);
		$bothAnswered = false;

		$counter = 10;
		while (!$bothAnswered && --$counter) {
			$matchData = $game->getMatchData();
			$bothAnswered = $game->bothHaveAnswered($matchData, $index);
			echo $counter;
			sleep(1);
		}

		$answer = $game->buildAnswer($matchData, $index, $question);
		foreach ($matchData['channels'] as $i => $channelId) {
			$userId = $matchData['users'][$i];
			$enemyUserId = $i ? $matchData['users'][0] : $matchData['users'][1];
			$answer['myAnswer'] = $matchData['answers'][$index][$userId];
			$answer['enemyAnswer'] = $matchData['answers'][$index][$enemyUserId] ?: false;
			$answer['correctAnswer'] = $question['answer'];
			$answer['myScore'] = (int) $matchData['score'][$userId];
			$answer['enemyScore'] = (int) $matchData['score'][$enemyUserId];
			$game->publish($channelId, json_encode($answer));
		}

		echo "Preparing next question...\n";
		if ($bothAnswered) {
			sleep(3);
		}
	}

	foreach ($matchData['channels'] as $i => $channelId) {
		$userId = $matchData['users'][$i];
		$enemyUserId = $i ? $matchData['users'][0] : $matchData['users'][1];
		$publishData['myScore'] = $matchData['score'][$userId];
		$publishData['enemyScore'] = $matchData['score'][$enemyUserId];
		// rank
		$game->publish($channelId, json_encode($publishData));
	}

	echo "Game finished\n";

} else {
	echo "Match data not found: $matchToken\n";
}

class Game
{
	const BASE_URL = 'http://128.199.146.154:88';
	const CACHE_KEY = 'matchmaker';
	const WAITING_TIME_SECOND = 100;
	private $memcached;
	private $matchToken;
	private $matchData;

	public function __construct($matchToken)
	{
		$this->matchToken = $matchToken;
		$this->memcached = new Memcached;
		$this->memcached->addServer('localhost', 11211);
	}

	public function getMatchData()
	{
		$this->matchData = $this->memcached->get('match_' . $this->matchToken);
		return $this->matchData;
	}

	public function sendToUsers($postData)
	{
		$postBody = json_encode($postData);
		foreach ($this->matchData['channels'] as $channelId) {
			echo "Sending $postBody to channel $channelId...\n";
			$this->publish($channelId, $postBody);
		}
	}

	public function publish($channelId, $postBody)
	{
		return $this->sendCurl("/pub?id=$channelId", 'POST', $postBody);
	}

	public function closeChannel($channelId)
	{
		return $this->sendCurl("/pub?id=$channelId", 'DELETE');
	}

	public function buildQuestion($index, $question)
	{
		unset($question['id'], $question['answer']);
		$time = floor(microtime(true) * 1000);
		$question['type'] = 'question';
		$question['gameId'] = $this->matchToken;
		$question['index'] = $index;
		$question['token'] = base_convert($time, 10, 36) . '.' . crc32($time . 'Hello, Novita!' . $index);
		$question['options'] = json_decode($question['options']);
		return $question;
	}

	public function buildAnswer($matchData, $index, $question)
	{
		unset($question['id']);
		$question['type'] = 'answer';
		$question['gameId'] = $this->matchToken;
		$question['index'] = $index;
		$question['options'] = json_decode($question['options']);
		return $question;
	}

	public function bothHaveAnswered($matchData, $index)
	{
		var_dump($matchData);
		if (!empty($matchData['answers'][$index])) {
			if (count($matchData['answers'][$index] == 2)) {
				return true;
			}
		}

		return false;
	}

	private function sendCurl($path, $method = 'POST', $postBody = '')
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($curl, CURLOPT_URL, self::BASE_URL . $path);
		// curl_setopt($curl, CURLOPT_FILETIME, true);
		curl_setopt($curl, CURLOPT_NOBODY, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POST, 1);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $postBody);
		// curl_setopt($curl, CURLOPT_VERBOSE, 1);

		$result = curl_exec($curl);
		$info = curl_getinfo($curl);
		curl_close($curl);
		return $result;
	}
}
