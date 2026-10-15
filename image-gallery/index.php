<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<?php
require '../config.php';
$images = $conn->query("SELECT * FROM images ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Gallery</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body { background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%); color: #e2e8f0; min-height: 100vh; }
        .container { width: 90%; max-width: 1100px; margin: auto; color: black; }

        header { position: sticky; top: 0; backdrop-filter: blur(10px); border-bottom: 1px solid rgba(255,255,255,0.08); z-index: 1000; }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 15px 0; font-size: x-large; color: black; }
        nav ul { display: flex; list-style: none; gap: 22px; align-items: center; }
        nav ul li a { color: #0a121c; text-decoration: none; font-weight: 500; transition: 0.3s; }
        nav ul li a:hover { color: #2ca8dd; }
        .auth-buttons { display: flex; gap: 10px; align-items: center; }
        .auth-buttons a { padding: 8px 14px; border-radius: 8px; text-decoration: none; font-weight: 600; }
        .login-btn { border: 1px solid #e838f8; color: #e838f8; }
        .signup-btn { background: #e838f8; color: white; }

        .hero { padding: 60px 0 30px 0; text-align: center; }
        .hero h1 { font-size: 52px; color: black; }
        .hero h1 span { color: purple; }
        .hero p { max-width: 600px; margin: 10px auto; color: black; }

        .upload-box {
            background: rgba(255,255,255,0.35);
            backdrop-filter: blur(10px);
            padding: 25px;
            border-radius: 16px;
            max-width: 500px;
            margin: 0 auto 35px auto;
            box-shadow: 0 4px 15px rgba(128,0,128,0.15);
            text-align: center;
            border: 1px solid rgba(128,0,128,0.2);
        }
        .upload-box h3 { margin-bottom: 15px; color: purple; font-size: 20px; }
        .upload-box input[type="file"] { margin: 10px 0 15px 0; width: 100%; color: black; }
        .upload-box button {
            padding: 10px 30px;
            border: 2px solid purple;
            border-radius: 10px;
            background: purple;
            color: white;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }
        .upload-box button:hover { background: transparent; color: purple; }

        .gallery { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; max-width: 1100px; margin: 0 auto 40px auto; }
        .card { background: rgba(255,255,255,0.35); backdrop-filter: blur(10px); border-radius: 14px; overflow: hidden; box-shadow: 0 4px 15px rgba(128,0,128,0.1); border: 1px solid rgba(128,0,128,0.15); }
        .card img { width: 100%; height: 180px; object-fit: cover; display: block; }
        .card-body { padding: 12px; }
        .card-body .fname { font-size: 13px; color: #333; margin-bottom: 4px; word-break: break-all; }
        .card-body .fdate { font-size: 11px; color: #666; margin-bottom: 10px; }
        .btn-row { display: flex; gap: 8px; }
        .btn {
            flex: 1;
            text-align: center;
            padding: 7px;
            border-radius: 8px;
            font-size: 13px;
            text-decoration: none;
            color: white;
            font-weight: 600;
            transition: 0.3s;
            border: 2px solid transparent;
        }
        .btn-download { background: purple; border-color: purple; }
        .btn-download:hover { background: transparent; color: purple; }
        .btn-drive { background: #e838f8; border-color: #e838f8; }
        .btn-drive:hover { background: transparent; color: #e838f8; }

        .no-images { text-align: center; color: #555; font-size: 16px; margin: 40px auto; }

        footer { width: 500px; border: 1px solid purple; border-radius: 20px; text-align: center; padding: 15px 20px; background: purple; color: white; display: flex; justify-content: center; margin: auto; margin-top: 10px; overflow: hidden; }
        footer ul { display: flex; list-style: none; gap: 40px; align-items: center; justify-content: center; flex-wrap: wrap; }
        footer ul li { word-break: break-word; text-align: center; font-size: 14px; }
        footer ul li a { color: white; text-decoration: none; }
        footer ul li a:hover { color: #f0c0ff; }
    </style>
</head>
<body>

<header>
    <div class="container">
        <nav>
            <div><strong>Image Gallery</strong></div>
            <ul>
                <li><a href="../index.php">Home</a></li>
            </ul>
        </nav>
    </div>
</header>

<div class="container">
    <div class="hero">
        <h1>My <span>Image</span> Gallery</h1>
        <p>Upload your images, view them instantly, download anytime and save directly to Google Drive.</p>
    </div>

    <!-- Upload Form -->
    <div class="upload-box">
        <h3>⬆ Upload a New Image</h3>
        <form action="upload.php" method="POST" enctype="multipart/form-data">
            <input type="file" name="image" accept="image/*" required>
            <button type="submit">Upload Image</button>
        </form>
    </div>

    <!-- Gallery -->
    <div class="gallery">
        <?php if ($images->num_rows === 0): ?>
            <p class="no-images">No images uploaded yet. Upload your first image above! 🖼</p>
        <?php else: ?>
            <?php while ($img = $images->fetch_assoc()): ?>
            <div class="card">
                <img src="uploads/<?= htmlspecialchars($img['filename']) ?>"
                     alt="<?= htmlspecialchars($img['original_name']) ?>">
                <div class="card-body">
                    <p class="fname"><?= htmlspecialchars($img['original_name']) ?></p>
                    <p class="fdate"><?= date('d M Y, h:i A', strtotime($img['uploaded_at'])) ?></p>
                    <div class="btn-row">
                        <a class="btn btn-download" href="download.php?id=<?= $img['id'] ?>">⬇ Download</a>
                        <a class="btn btn-drive" href="<?= htmlspecialchars($img['drive_link']) ?>" target="_blank">☁ Drive</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        <?php endif; ?>
    </div>
</div>

 <footer>
        <ul>
            <li>hassanqadri047@gmail.com</li>
            <li>Nankana Sahib</li>
        </ul>
    </footer>


</body>
</html>