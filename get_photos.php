<?php
session_start();
include "config.php";

header('Content-Type: application/json');


if (!isset($_SESSION['email'])) {
    echo json_encode([]);
    exit;
}


$email     = mysqli_real_escape_string($conn, $_SESSION['email']);
$userQuery = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email' LIMIT 1");

if (!$userQuery || mysqli_num_rows($userQuery) === 0) {
    echo json_encode([]);
    exit;
}

$userRow = mysqli_fetch_assoc($userQuery);
$current_email = $_SESSION['email'];   


$sql = "SELECT 
            image       AS file,
            description AS description,
            visibility  AS valid,
            upload_time
        FROM gallery
        WHERE user_email = '$current_email'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode([]);
    mysqli_close($conn);
    exit;
}


$photos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $photos[] = $row;
}

echo json_encode($photos);

mysqli_close($conn);
?>