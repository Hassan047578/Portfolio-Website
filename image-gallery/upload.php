<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(120); // give it 2 minutes to complete
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php
require '../config.php';
require 'drive.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {

    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    $file    = $_FILES['image'];

    if (!in_array($file['type'], $allowed)) {
        showError("❌ Only JPG, PNG, GIF, WEBP images are allowed.");
    }

    if ($file['size'] > 5 * 1024 * 1024) {
        showError("❌ File too large. Maximum size is 5MB.");
    }

    $ext        = pathinfo($file['name'], PATHINFO_EXTENSION);
    $uniqueName = uniqid('img_', true) . '.' . $ext;
    $uploadDir  = __DIR__ . '/uploads/';
    $uploadPath = $uploadDir . $uniqueName;

    if (!move_uploaded_file($file['tmp_name'], $uploadPath)) {
        showError("❌ Failed to save image on server.");
    }

    $driveResult = uploadToDrive($uploadPath, $uniqueName);

    $stmt = $conn->prepare(
        "INSERT INTO images (filename, original_name, drive_file_id, drive_link) VALUES (?, ?, ?, ?)"
    );
    $stmt->bind_param('ssss', $uniqueName, $file['name'], $driveResult['file_id'], $driveResult['link']);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;

} else {
    showError("❌ No file was uploaded.");
}

function showError($msg) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Error</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%); min-height: 100vh; display: flex; flex-direction: column; }
        .container { width: 90%; max-width: 1100px; margin: auto; color: black; }
        header { position: sticky; top: 0; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.08); z-index: 1000; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; font-size: x-large; color: black; }
        nav ul { display: flex; list-style: none; gap: 22px; }
        nav ul li a { color: #0a121c; text-decoration: none; font-weight: 500; transition: 0.3s; }
        nav ul li a:hover { color: #2ca8dd; }

        .error-box {
            background: rgba(255,255,255,0.35);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(128,0,128,0.2);
            border-radius: 16px;
            max-width: 500px;
            margin: 80px auto;
            padding: 40px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(128,0,128,0.15);
        }
        .error-box h2 { color: purple; font-size: 24px; margin-bottom: 15px; }
        .error-box p { color: #333; font-size: 16px; margin-bottom: 25px; }
        .back-btn {
            display: inline-block;
            padding: 10px 30px;
            border: 2px solid purple;
            border-radius: 10px;
            background: purple;
            color: white;
            font-size: 15px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
        }
        .back-btn:hover { background: transparent; color: purple; }

        footer { width: 500px; border: 1px solid purple; border-radius: 20px; text-align: center; padding: 15px 20px; background: purple; color: white; display: flex; justify-content: center; margin: auto; margin-top: auto; overflow: hidden; }
        footer ul { display: flex; list-style: none; gap: 40px; align-items: center; justify-content: center; flex-wrap: wrap; }
        footer ul li { font-size: 14px; }
        footer ul li a { color: white; text-decoration: none; }
    </style>
</head>
<body>

<header>
    <div class="container">
        <nav>
            <div><strong>🖼 Image Gallery</strong></div>
            <ul><li><a href="index.php">Home</a></li></ul>
        </nav>
    </div>
</header>

<div class="container">
    <div class="error-box">
        <h2>Upload Failed</h2>
        <p><?= htmlspecialchars($msg) ?></p>
        <a class="back-btn" href="index.php">← Go Back</a>
    </div>
</div>

 <footer>
        <ul>
            <li>hassanqadri047@gmail.com</li>
            <li>Nankana Sahib</li>
        </ul>
    </footer>

</body>
</html>
<?php
    exit;
}
?>