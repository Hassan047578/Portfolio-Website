<?php
include "config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id   = mysqli_real_escape_string($conn, $_POST['id']);
    $user = $_SESSION['user_id'];

    $query = "DELETE FROM my_favourite_place WHERE id='$id' AND user_id='$user'";

    mysqli_query($conn, $query);
}

header("Location: show.php");
exit;
?>
