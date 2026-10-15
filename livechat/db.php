<?php
try {
    $db = new PDO("mysql:host=localhost;dbname=hassisbs_livechat;charset=utf8", "hassisbs_use", "p)0!6?weUjEI");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>