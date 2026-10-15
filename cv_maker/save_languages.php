<?php
session_start();

include "../config.php";

$user_id       = $_SESSION['cv_user'];
$language_name = $_POST["language_name"];
$proficiency   = $_POST["proficiency"];

$sql = "INSERT INTO languages (user_id, language_name, proficiency)
        VALUES ('$user_id', '$language_name', '$proficiency')";

if (mysqli_query($conn, $sql)) {
    header("Location: cv.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>