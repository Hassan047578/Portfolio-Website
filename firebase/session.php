<?php
session_start();
header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['ok' => false, 'error' => 'No input']);
    exit;
}

$action = $input['action'] ?? '';

if ($action === 'login') {
    $uid   = trim($input['uid']   ?? '');
    $name  = trim($input['name']  ?? '');
    $email = trim($input['email'] ?? '');

    if (!$uid || !$name || !$email) {
        echo json_encode(['ok' => false, 'error' => 'Missing fields']);
        exit;
    }

    $_SESSION['user_id']    = $uid;
    $_SESSION['user_name']  = $name;
    $_SESSION['user_email'] = $email;

    echo json_encode(['ok' => true]);

} elseif ($action === 'logout') {
    session_destroy();
    echo json_encode(['ok' => true]);

} else {
    echo json_encode(['ok' => false, 'error' => 'Unknown action']);
}
?>