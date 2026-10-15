<?php
include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name     = trim($_POST['user_name']);
    $email    = trim($_POST['user_email']);
    $password = $_POST['user_password'];

    try {
       
        $check = $db->prepare("SELECT id FROM sign_up WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetch()) {
            echo "Error: Email already registered.";
            exit();
        }

        $hash_password = password_hash($password, PASSWORD_BCRYPT);

        
        $query = $db->prepare("INSERT INTO sign_up (name, email, password) 
                               VALUES (?, ?, ?)");

        if ($query->execute([$name, $email, $hash_password])) {
            echo "User registered successfully.";
        } else {
            echo "Error: Could not register user.";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>