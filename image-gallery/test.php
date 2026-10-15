<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/vendor/autoload.php';

$client = new Google\Client();
$client->setAuthConfig(__DIR__ . '/credentials.json');
$client->addScope(Google\Service\Drive::DRIVE);

$service = new Google\Service\Drive($client);

$FOLDER_ID = '1K3KuNEwgcLh0clUjtwwR1pYzeJ00Qaov';

echo "<h2>Testing Google Drive Connection...</h2>";
echo "<p>Service Account: image-uploader@image-upload-495715.iam.gserviceaccount.com</p>";
echo "<p>Folder ID: " . $FOLDER_ID . "</p>";
echo "<hr>";

try {
    $folder = $service->files->get($FOLDER_ID, [
        'supportsAllDrives' => true
    ]);
    echo "<h2 style='color:green'>✅ SUCCESS! Folder found: " . $folder->getName() . "</h2>";
    echo "<p style='color:green'>Google Drive is connected correctly! You can now upload images.</p>";
} catch (Exception $e) {
    echo "<h2 style='color:red'>❌ FAILED — Folder not accessible</h2>";
    echo "<p style='color:red'>Error: " . $e->getMessage() . "</p>";
    echo "<hr>";
    echo "<h3>Most likely reason:</h3>";
    echo "<p>The folder is NOT shared with the service account email.</p>";
    echo "<p>Please share your Google Drive folder with:</p>";
    echo "<strong>image-uploader@image-upload-495715.iam.gserviceaccount.com</strong>";
}
?>