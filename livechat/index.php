<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $useremail = trim($_POST['useremail']);
    $password  = $_POST['password'];

    $stmt = $db->prepare("SELECT * FROM sign_up WHERE email = ?");
    $stmt->execute([$useremail]);
    $user = $stmt->fetch();

    if ($user === false) {
        $error = "No account found with this email.";
    } elseif (!password_verify($password, $user['password'])) {
        $error = "Incorrect password.";
    } else {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        header("Location: chat.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hassan Wazir | Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            color: black;
        }

        /* ── Nav ── */
        header {
            position: sticky;
            top: 0;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
            z-index: 1000;
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            font-size: x-large;
            color: black;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 22px;
            align-items: center;
        }

        nav ul li a {
            color: #0a121c;
            text-decoration: none;
            font-weight: 500;
            transition: 0.3s;
        }

        nav ul li a:hover {
            color: #2ca8dd;
        }

        .auth-buttons {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .auth-buttons a {
            padding: 8px 14px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }

        .login-btn {
            border: 1px solid #e838f8;
            color: #e838f8;
        }

        .signup-btn {
            background: #e838f8;
            color: white;
        }

        .makecv-btn {
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            background: purple;
            color: white !important;
            border: 2px solid purple;
            font-size: 15px;
            transition: 0.3s;
        }

        .makecv-btn:hover {
            background: transparent;
            color: purple !important;
        }

        /* ── Page Section ── */
        .page-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0 30px;
        }

        /* ── Login Card ── */
        .login-card {
            width: 100%;
            max-width: 380px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(128, 0, 128, 0.15);
            padding: 36px 32px;
            color: black;
            text-align: center;
        }

        .login-card .icon {
            width: 60px;
            height: 60px;
            background: purple;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin: 0 auto 16px;
        }

        .login-card h2 {
            font-size: 24px;
            font-weight: 700;
            color: black;
            margin-bottom: 6px;
        }

        .login-card h2 span {
            color: purple;
        }

        .login-card p.subtitle {
            font-size: 13px;
            color: #666;
            margin-bottom: 24px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 11px 14px;
            margin-bottom: 14px;
            border: 1px solid rgba(128, 0, 128, 0.3);
            border-radius: 10px;
            font-size: 14px;
            color: #222;
            background: rgba(255, 255, 255, 0.8);
            outline: none;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: purple;
        }

        input::placeholder {
            color: #999;
        }

        .submit-btn {
            width: 100%;
            padding: 11px;
            background: purple;
            color: white;
            border: 2px solid purple;
            border-radius: 10px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 4px;
        }

        .submit-btn:hover {
            background: transparent;
            color: purple;
        }

        .error {
            margin-top: 12px;
            font-size: 13px;
            color: red;
            background: rgba(255, 0, 0, 0.08);
            border-radius: 8px;
            padding: 8px 12px;
        }

        .footer-text {
            margin-top: 18px;
            font-size: 13px;
            color: #555;
        }

        .footer-text a {
            color: purple;
            text-decoration: none;
            font-weight: 600;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        /* ── Footer ── */
        footer {
            width: 500px;
            border: 1px solid purple;
            border-radius: 20px;
            text-align: center;
            padding: 15px 20px;
            background: purple;
            color: white;
            display: flex;
            justify-content: center;
            margin: auto;
            margin-top: 10px;
            margin-bottom: 15px;
            overflow: hidden;
        }

        footer ul {
            display: flex;
            list-style: none;
            gap: 40px;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        footer ul li {
            word-break: break-word;
            text-align: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <header>
        <div class="container">
            <nav>
                <ul id="menu">
                    <!-- <li><a href="#skills">Skills</a></li>
                    <li><a href="tutorial.php">Tutorials</a></li>
                    <li><a href="public_gallery.php">Gallery</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="QR.php">QR</a></li>
                    <li><a href="chatting/index.php">Chat</a></li>
                    <li><a href="location/location.php">Location</a></li>
                </ul> -->
                <div class="auth-buttons">
                    <!-- <a href="cv_maker/bio.php" class="makecv-btn">Make CV</a> -->
                    <!-- <a href="login.php" class="login-btn">Login</a>
                    <a href="signup.php" class="signup-btn">Signup</a>
                </div>
            </nav> -->
        </div>
    </header>

    <section class="page-section">
        <div class="login-card">

            <div class="icon">🔐</div>
            <h2>Welcome <span>Back</span></h2>
            <p class="subtitle">Login to your account to continue</p>

            <form method="POST" action="index.php">
                <input type="email" name="useremail" placeholder="email@gmail.com" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" class="submit-btn">Login</button>

                <?php if ($error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endif; ?>
            </form>

            <p class="footer-text">Don't have an account? <a href="signup.html">Sign Up</a></p>

        </div>
    </section>

    <!-- <footer>
        <ul>
            <li>hassanqadri047@gmail.com</li>
            <li>Nankana Sahib</li>
        </ul>
    </footer> -->

</body>
</html>