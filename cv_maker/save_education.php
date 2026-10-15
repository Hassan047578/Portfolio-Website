<?php
session_start();

include "../config.php";

$user_id       = $_SESSION['cv_user'];
$qualification = $_POST["qualification"];
$board         = $_POST["board"];
$institute     = $_POST["institute"];
$subject       = $_POST["subject"];
$obtained      = $_POST["obtained_marks"];
$total         = $_POST["total_marks"];
$year          = $_POST["passing_year"];

$sql = "INSERT INTO education
        (user_id, qualification, board, institute, subject, obtained_marks, total_marks, passing_year)
        VALUES ('$user_id', '$qualification', '$board', '$institute', '$subject', '$obtained', '$total', '$year')";

if (mysqli_query($conn, $sql)) {
    header("Location: projects.php");
} else {
    echo "Error: " . mysqli_error($conn);
}
?>