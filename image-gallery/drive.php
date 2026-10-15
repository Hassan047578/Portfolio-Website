<?php
require_once __DIR__ . '/vendor/autoload.php';

function uploadToDrive($filePath, $fileName) {

    $FOLDER_ID = '1K3KuNEwgcLh0clUjtwwR1pYzeJ00Qaov'; // Your Google Drive folder ID

    $client = new Google\Client();
    $client->setAuthConfig(__DIR__ . '/oauth_credentials.json');
    $client->addScope(Google\Service\Drive::DRIVE_FILE);
    $client->setRedirectUri('https://hassanwazir.online/image-gallery/oauth_callback.php'); // ← change to your domain
    $client->setAccessType('offline');

    // Load saved token
    $tokenFile = __DIR__ . '/token.json';
    if (!file_exists($tokenFile)) {
        die("❌ Google Drive not connected. <a href='google_auth.php'>Click here to connect Google Drive</a>");
    }

    $token = json_decode(file_get_contents($tokenFile), true);
    $client->setAccessToken($token);

    // Refresh token if expired
    if ($client->isAccessTokenExpired()) {
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            // Save new token
            file_put_contents($tokenFile, json_encode($client->getAccessToken()));
        } else {
            die("❌ Token expired. <a href='google_auth.php'>Click here to reconnect Google Drive</a>");
        }
    }

    $service = new Google\Service\Drive($client);

    $fileMetadata = new Google\Service\Drive\DriveFile([
        'name'    => $fileName,
        'parents' => [$FOLDER_ID]
    ]);

    $content = file_get_contents($filePath);

    $file = $service->files->create($fileMetadata, [
        'data'       => $content,
        'mimeType'   => mime_content_type($filePath),
        'uploadType' => 'multipart',
        'fields'     => 'id, webViewLink'
    ]);

    // Make file publicly viewable
    $permission = new Google\Service\Drive\Permission([
        'type' => 'anyone',
        'role' => 'reader'
    ]);
    $service->permissions->create($file->id, $permission);

    return [
        'file_id' => $file->id,
        'link'    => $file->webViewLink
    ];
}
?>