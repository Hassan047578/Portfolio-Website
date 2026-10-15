<?php
session_start();

include "../config.php";

$user_id      = $_SESSION['cv_user'];
$title        = $_POST["title"];
$technologies = $_POST["technologies"];
$description  = $_POST["description"];
$year         = $_POST["year"];

$sql = "INSERT INTO projects (user_id, title, technologies, description, year)
        VALUES ('$user_id', '$title', '$technologies', '$description', '$year')";

if (mysqli_query($conn, $sql)) {
    header("Location: skills.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>