<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include '../config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "INSERT INTO users (name, email, password) 
            VALUES ('$name', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {

        header("Location: ../login.php");
        exit();

    } else {
        echo "Database Error: " . $conn->error;
    }

} else {
    echo "Invalid Request";
}
?>