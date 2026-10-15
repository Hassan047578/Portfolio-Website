<?php
include "config.php";

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');


$sql = "SELECT 
            image,
            description ,
            user_email,  
            upload_time
        FROM gallery
        WHERE visibility = 'public'
        ORDER BY id DESC";

$result = mysqli_query($conn, $sql);

if (!$result) {
    echo json_encode(["error" => mysqli_error($conn)]);
    mysqli_close($conn);
    exit;
}

$photos = [];
while ($row = mysqli_fetch_assoc($result)) {
    $photos[] = [
        "file"        => $row['image'],
        "description" => $row['description'],
        "created_at"  => $row['upload_time'],
        "username"    => !empty($row['user_email']) ? $row['user_email'] : "Anonymous"
    ];
}

echo json_encode($photos);
mysqli_close($conn);
?>