<?php
session_start();

include "../config.php";

$user_id     = $_SESSION['cv_user'];
$skill_name  = $_POST["skill_name"];
$skill_level = $_POST["skill_level"];

$sql = "INSERT INTO skills (user_id, skill_name, skill_level)
        VALUES ('$user_id', '$skill_name', '$skill_level')";

if (mysqli_query($conn, $sql)) {
    header("Location: languages.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>