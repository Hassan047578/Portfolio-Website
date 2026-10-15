<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['error' => 'Invalid method']);
    exit;
}

if (empty($_FILES['chat_file']['name'])) {
    echo json_encode(['error' => 'No file received']);
    exit;
}

// ── Validation ────────────────────────────────────────────
$allowed_types = [
    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'text/plain',
    'video/mp4', 'video/webm',
];
$allowed_exts = ['jpg','jpeg','png','gif','webp','pdf','doc','docx','txt','mp4','webm'];

$ftype = mime_content_type($_FILES['chat_file']['tmp_name']);
$fsize = $_FILES['chat_file']['size'];
$ext   = strtolower(pathinfo($_FILES['chat_file']['name'], PATHINFO_EXTENSION));

if (!in_array($ftype, $allowed_types)) {
    http_response_code(415);
    echo json_encode(['error' => 'File type not allowed.']);
    exit;
}

if (!in_array($ext, $allowed_exts)) {
    http_response_code(415);
    echo json_encode(['error' => 'Invalid file extension.']);
    exit;
}

if ($fsize > 10 * 1024 * 1024) {
    http_response_code(413);
    echo json_encode(['error' => 'File too large (max 10 MB).']);
    exit;
}

// ── Save locally ──────────────────────────────────────────
$upload_dir = __DIR__ . '/uploads/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

$safe_filename = uniqid('chat_', true) . '.' . $ext;
$dest = $upload_dir . $safe_filename;

if (!move_uploaded_file($_FILES['chat_file']['tmp_name'], $dest)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save file.']);
    exit;
}

// Return the public URL path
$file_url = 'uploads/' . $safe_filename;

echo json_encode([
    'ok'        => true,
    'url'       => $file_url,
    'file_type' => $ftype,
    'filename'  => $_FILES['chat_file']['name'],
]);
?>