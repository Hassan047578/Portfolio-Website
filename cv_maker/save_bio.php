<?php
session_start();
include "../config.php";

if (!isset($_SESSION['cv_user'])) {
    die("Session not found");
}

$user_id = $_SESSION['cv_user'];


$full_name = mysqli_real_escape_string($conn, $_POST["full_name"]);
$email     = mysqli_real_escape_string($conn, $_POST["email"]);
$phone     = mysqli_real_escape_string($conn, $_POST["phone"]);
$city      = mysqli_real_escape_string($conn, $_POST["city"]);


$check = mysqli_query($conn, "SELECT id FROM bio WHERE user_id = '$user_id'");

if (mysqli_num_rows($check) > 0) {

    
    $sql = "UPDATE bio SET 
                full_name = '$full_name',
                email = '$email',
                phone = '$phone',
                city = '$city'
            WHERE user_id = '$user_id'";

} else {

    
    $sql = "INSERT INTO bio (user_id, full_name, email, phone, city)
            VALUES ('$user_id', '$full_name', '$email', '$phone', '$city')";
}

if (mysqli_query($conn, $sql)) {
    header("Location: education.php");
    exit();
} else {
    echo "Error: " . mysqli_error($conn);
}
?>