<?php
session_start();
include 'db.php';

if(!isset($_SESSION['user_id'])) exit;

$current_user_id = $_SESSION['user_id'];

$stmt = $db->prepare("
    SELECT messages.id, messages.message, messages.image_path, messages.created_at,
           messages.user_id, sign_up.name
    FROM messages
    JOIN sign_up ON messages.user_id = sign_up.id
    ORDER BY messages.created_at ASC
");
$stmt->execute();
$messages = $stmt->fetchAll();

foreach($messages as $row){
    $isOwn      = ($row['user_id'] == $current_user_id) ? 'own' : '';
    $name       = htmlspecialchars($row['name']);
    $msg        = htmlspecialchars($row['message']);
    $time       = date('h:i A', strtotime($row['created_at']));
    $image_path = $row['image_path'] ? htmlspecialchars($row['image_path']) : '';

    $words    = explode(' ', $name);
    $initials = strtoupper(substr($words[0], 0, 1));
    if(isset($words[1])) $initials .= strtoupper(substr($words[1], 0, 1));

    $bubbleContent = '';

    if($image_path){
        $fileName = basename($image_path);
        $uid = 'img_' . md5($image_path . $row['id']);

        if($isOwn === 'own'){
            // SENDER: sees image clearly with no blur or download button
            $bubbleContent .= "
            <div class='bubble image-bubble'>
                <img class='chat-img revealed'
                     src='{$image_path}'
                     alt='Image'
                     onclick='openLightbox(\"{$image_path}\", \"{$fileName}\")'>
            </div>";
        } else {
            // RECEIVER: image is blurred until they click download
            $bubbleContent .= "
            <div class='bubble image-bubble' id='wrap_{$uid}'>
                <div class='img-blur-wrap' id='blur_{$uid}'>
                    <img class='chat-img blurred' src='{$image_path}' alt='Image'>
                    <button class='dl-overlay-btn'
                            onclick='downloadImage(\"{$image_path}\", \"{$fileName}\", \"{$uid}\")'
                            title='Download to view'>
                        &#8675;
                    </button>
                </div>
                <img class='chat-img revealed'
                     id='revealed_{$uid}'
                     src='{$image_path}'
                     alt='Image'
                     style='display:none;'
                     onclick='openLightbox(\"{$image_path}\", \"{$fileName}\")'>
            </div>";
        }

        if($msg !== ''){
            $bubbleContent .= "<div class='bubble' style='margin-top:4px;'>{$msg}</div>";
        }
    } else {
        $bubbleContent .= "<div class='bubble'>{$msg}</div>";
    }

    echo "
    <div class='message-row {$isOwn}'>
        <div class='avatar'>{$initials}</div>
        <div class='bubble-wrap'>
            <div class='sender-name'>{$name}</div>
            {$bubbleContent}
            <div class='msg-time'>{$time}</div>
        </div>
    </div>
    ";
}
?>