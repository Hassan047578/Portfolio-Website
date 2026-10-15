<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

require_once __DIR__ . '/vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig(__DIR__ . '/oauth_credentials.json');
$client->addScope(Google\Service\Drive::DRIVE_FILE);
$client->setRedirectUri('https://hassanwazir.online/image-gallery/oauth_callback.php'); // ← change to your domain

if (isset($_GET['code'])) {
    // Exchange code for access token
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

    if (isset($token['error'])) {
        die("❌ Error getting token: " . $token['error_description']);
    }

    // Save token to file
    file_put_contents(__DIR__ . '/token.json', json_encode($token));

    echo "<h2 style='color:green'>✅ Google Drive Connected Successfully!</h2>";
    echo "<p>You can now <a href='index.php'>go back to your gallery</a> and upload images.</p>";
} else {
    die("❌ No code received from Google.");
}
?>