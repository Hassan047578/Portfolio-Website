<?php

$file = "counter.txt";


if (!file_exists($file)) {
    file_put_contents($file, "0");
}


$count = (int)file_get_contents($file);


$count++;


file_put_contents($file, $count);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hassan Wazir | Portfolio</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
            color: #e2e8f0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: auto;
            color: black;
        }

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

        .hero {
            height: 500px;
            padding-top: 100px;
            text-align: center;
        }

        .hero h1 {
            font-size: 52px;
        }

        .hero h1 span {
            color: purple;
        }

        .hero p {
            max-width: 600px;
            margin: 10px auto;
            color: black;
        }

        .btn {
            width: 100px;
            height: 40px;
            border: 1px solid purple;
            display: inline-block;
            margin-top: 20px;
            background: transparent;
            color: purple;
            border-radius: 10px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.3s;
            margin-left: 40px;
        }

        .btn:hover {
            color: white;
            background-color: purple;
        }

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

        .new {
            color: black;
        }

        
        .visitor-box {
            width: 220px;
            margin: 20px auto;
            padding: 10px;
            background: purple;
            color: white;
            border-radius: 10px;
            text-align: center;
            font-weight: bold;
        }
    </style>

</head>

<body>

    <header>
        <div class="container">
            <nav>

                <ul id="menu">
                    
                    <li><a href="#skills">Skills</a></li>
                    <li><a href="tutorial.php">Tutorials</a></li>
                    <li><a href="public_gallery.php">Gallery</a></li>
                    <li><a href="#projects">Projects</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="QR.php">QR</a></li>
                    <!--<li><a href="chatting/index.php">Chat</a></li>-->
                    <li><a href="location/location.php">Location</a></li>
                     <li><a href="image-gallery/index.php">Drive</a></li>
                    

                </ul>

                <div class="auth-buttons">
                    <a href="cv_maker/bio.php" class="makecv-btn">Make CV</a>
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="signup.php" class="signup-btn">Signup</a>
                    <!--<a href="image-gallery/index.php" class="makecv-btn">🖼 Image Gallery</a>-->
                </div>

            </nav>
        </div>
    </header>

    
    <section class="hero">
        <div class="container">
            <h1>Hello, I'm <span>Hassan Wazir</span></h1>
            <p class="new">
                Frontend Developer & MERN Stack Developer from Pakistan...
            </p>

            <button class="btn">Hire me</button>
            <button class="btn">Let's Talk</button>

        
            <div class="visitor-box">
                👁 Visitors: <?php echo $count; ?>
            </div>

        </div>
    </section>

    <footer>
        <ul>
            <li>hassanqadri047@gmail.com</li>
            <li>Nankana Sahib</li>
        </ul>
    </footer>

</body>
</html>