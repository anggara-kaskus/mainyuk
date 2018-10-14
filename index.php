<?php
error_reporting(E_ALL);
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db/db.php';
require_once __DIR__ . '/db/auth.php';

# Log me out
if (isset($_REQUEST['logout'])) {
	unset($_SESSION['googleIdToken']);
}

# Auth Code --> Access Token
if (isset($_GET['code'])) {
	$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
	$_SESSION['googleIdToken'] = $token;
	$_SESSION['userChannel'] = 'U' . md5($token . microtime(true));
	header('Location: ' . filter_var($redirect_uri, FILTER_SANITIZE_URL));
	return;
}

if ($currentUser = getCurrentUser()) {
	include 'wait.php';
} else {
	session_destroy();
	$authUrl = $client->createAuthUrl();
	require 'login.php';
}
