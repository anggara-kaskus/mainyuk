<?php

session_start();
$redirect_uri = BASE_URL;
$oauth_credentials = './private/oc.json';
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
		return $token_data;
	}
	return false;
}
