<?php
session_start();
include "config.php";

header('Content-Type: application/json');


if (!isset($_SESSION['email'])) {
    echo json_encode(["success" => false, "message" => "Not logged in."]);
    exit;
}


$email     = mysqli_real_escape_string($conn, $_SESSION['email']);
$userQuery = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");

if (!$userQuery || mysqli_num_rows($userQuery) === 0) {
    echo json_encode(["success" => false, "message" => "User not found."]);
    exit;
}

$userRow = mysqli_fetch_assoc($userQuery);
$user_email = $_SESSION['email'];   


if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(["success" => false, "message" => "No file received or upload error."]);
    exit;
}

$file  = $_FILES['photo'];
$desc  = isset($_POST['desc'])  ? mysqli_real_escape_string($conn, trim($_POST['desc']))  : '';
$valid = isset($_POST['valid']) ? mysqli_real_escape_string($conn, trim($_POST['valid'])) : 'Public';


if (!in_array($valid, ['Public', 'Private'])) {
    $valid = 'Public';
}


$allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
$mime    = mime_content_type($file['tmp_name']);
if (!in_array($mime, $allowed)) {
    echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, PNG, GIF, WEBP allowed."]);
    exit;
}


$ext       = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
$filename  = uniqid('photo_', true) . '.' . $ext;
$uploadDir = 'image/';

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
    echo json_encode(["success" => false, "message" => "Failed to save the file."]);
    exit;
}

$sql = "INSERT INTO gallery (image, description, visibility, user_email, upload_time)
        VALUES ('$filename', '$desc', '$valid', '$user_email', NOW())";

if (mysqli_query($conn, $sql)) {
    echo json_encode(["success" => true, "message" => "Photo uploaded successfully!"]);
} else {
    unlink($uploadDir . $filename); 
    echo json_encode(["success" => false, "message" => "DB error: " . mysqli_error($conn)]);
}

mysqli_close($conn);
?>