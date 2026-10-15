<?php
include "config.php";

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $place = $_POST['place_name'];
    $lat   = $_POST['lat'];
    $lng   = $_POST['lng'];
    $user  = $_SESSION['user_id'];

    if ($lat == "" || $lng == "") {
        echo "<script>
            alert('Please select location on map first!');
            history.back();
        </script>";
        exit;
    }

    $sql = "INSERT INTO my_favourite_place 
            (user_id, place_name, location_latitude, location_longitude)
            VALUES 
            ('$user', '$place', '$lat', '$lng')";

    if (mysqli_query($conn, $sql)) {
        header("Location: show.php");
        exit;
    } else {
        echo "Error: " . mysqli_error($conn);
    }

} else {
    header("Location: location.php");
    exit;
}
?>