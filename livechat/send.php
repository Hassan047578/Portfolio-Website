<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])){
    http_response_code(403);
    exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $user_id    = $_SESSION['user_id'];
    $message    = trim($_POST['message'] ?? '');
    $image_path = null;

    // ── Handle image upload ──
    if(isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK){
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $file_type     = mime_content_type($_FILES['image']['tmp_name']);

        if(!in_array($file_type, $allowed_types)){
            http_response_code(415);
            exit('Unsupported file type.');
        }

        // Max 5 MB
        if($_FILES['image']['size'] > 5 * 1024 * 1024){
            http_response_code(413);
            exit('File too large. Max 5 MB.');
        }

        $upload_dir = 'uploads/chat/';
        if(!is_dir($upload_dir)){
            mkdir($upload_dir, 0755, true);
        }

        $ext       = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $safe_ext  = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']) ? strtolower($ext) : 'jpg';
        $file_name = uniqid('img_', true) . '.' . $safe_ext;
        $dest      = $upload_dir . $file_name;

        if(move_uploaded_file($_FILES['image']['tmp_name'], $dest)){
            $image_path = $dest;
        } else {
            http_response_code(500);
            exit('Upload failed.');
        }
    }

    // Must have at least a message or an image
    if($message === '' && $image_path === null){
        exit;
    }

    $stmt = $db->prepare("INSERT INTO messages (user_id, message, image_path, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$user_id, $message, $image_path]);
}
?>