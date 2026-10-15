<?php
session_start();
include("../config.php");
include("../sendmail.php");



if (isset($_POST['google_login']) && $_POST['google_login'] === '1') {

    $google_email = mysqli_real_escape_string($conn, $_POST['google_email']);
    $google_name  = mysqli_real_escape_string($conn, $_POST['google_name']);

    
    $query  = "SELECT * FROM users WHERE email='$google_email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        
        $row = mysqli_fetch_assoc($result);

        $_SESSION['user']    = $row['name'];
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['email']   = $row['email'];

        sendLoginEmail($google_email);
        header("Location: ../dashboard.php");
        exit;

    } else {
        
        $random_password = bin2hex(random_bytes(8));

        $insert = "INSERT INTO users (name, email, password) 
                   VALUES ('$google_name', '$google_email', '$random_password')";

        if (mysqli_query($conn, $insert)) {
            
            $new_id = mysqli_insert_id($conn);

            $_SESSION['user']    = $google_name;
            $_SESSION['user_id'] = $new_id;
            $_SESSION['email']   = $google_email;

            sendLoginEmail($google_email);
            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "<script>
                alert('Could not create account. Please try again.');
                window.location.href='../login.php';
            </script>";
            exit;
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    
    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        
        if ($password == $row['password']) {
            $_SESSION['user']    = $row['name'];
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['email']   = $row['email'];

            sendLoginEmail($email);
            header("Location: ../dashboard.php");
            exit;
        } else {
            echo "<script>
                alert('Wrong Password');
                window.location.href='../login.php';
            </script>";
        }
    } else {
        echo "<script>
            alert('User not found');
            window.location.href='../login.php';
        </script>";
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>