<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig(__DIR__ . '/oauth_credentials.json');
$client->addScope(Google\Service\Drive::DRIVE_FILE);
$client->setRedirectUri('https://hassanwazir.online/image-gallery/oauth_callback.php');
$client->setAccessType('offline');
$client->setPrompt('consent');

// Show the exact URL being used
echo "Redirect URI being sent to Google: <br>";
echo "<strong>https://hassanwazir.online/image-gallery/oauth_callback.php</strong>";
echo "<br><br>";
echo "<a href='" . $client->createAuthUrl() . "'>Click here to login with Google</a>";
?>