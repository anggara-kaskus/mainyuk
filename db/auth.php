<?php

session_start();
$redirect_uri = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
$oauth_credentials = './oc.json';

$client = new Google_Client();
$client->setAuthConfig($oauth_credentials);
$client->setRedirectUri($redirect_uri);
$client->setScopes('email');

function getCurrentUser()
{
	global $client;
	if (!empty($_SESSION['googleIdToken']) && isset($_SESSION['googleIdToken']['id_token'])) {
		$client->setAccessToken($_SESSION['googleIdToken']);
		$start = microtime(true);

		try {
			$token_data = $client->verifyIdToken();
			if (!empty($token_data)) {
				$token_data['userChannel'] = $_SESSION['userChannel'];
			}
		} catch(Exception $e) {
			die($e->getMessage());
		}
		#echo "Elapsed: " . round((microtime(true) - $start) / 1000, 4);
		return $token_data;
	}

	// if ($access_token = $client->getAccessToken()) {
	// }
	return false;
}
