<?php 
session_start();
include "authentication.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Gallery | Hassan Wazir</title>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
      color: #0a121c;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    a { color: inherit; text-decoration: none; }

    
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
      padding: 15px 5vw;
      font-size: large;
      color: black;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #38bdf8;
    }

    .nav-links {
      display: flex;
      gap: 22px;
      align-items: center;
      list-style: none;
    }

    .nav-links a {
      color: #0a121c;
      text-decoration: none;
      font-weight: 500;
      transition: 0.3s;
    }

    .nav-links a:hover { color: #2ca8dd; }
    .nav-links a.active { color: purple; font-weight: 700; }

    .nav-links a.logout {
      padding: 8px 14px;
      border-radius: 8px;
      border: 1px solid #e838f8;
      color: #e838f8;
      font-weight: 600;
      transition: 0.3s;
    }

    .nav-links a.logout:hover {
      background: #e838f8;
      color: white;
    }

    
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 3rem 5vw 5rem;
      max-width: 1200px;
      width: 100%;
      margin: 0 auto;
    }

    .section-label {
      font-size: .85rem;
      color: purple;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: .5rem;
      font-weight: 600;
    }

    .section-title {
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: bold;
      letter-spacing: -.02em;
      line-height: 1.1;
      margin-bottom: 2rem;
      color: #0a121c;
    }

    .section-title span { color: purple; }

    
    .alert {
      padding: .9rem 1.25rem;
      border-radius: 10px;
      font-size: .85rem;
      margin-bottom: 1.5rem;
      display: none;
      font-weight: 600;
    }

    .alert-success {
      background: rgba(56, 191, 248, 0.15);
      border: 1px solid rgba(56, 191, 248, 0.4);
      color: #0a121c;
    }

    .alert-error {
      background: rgba(232, 56, 248, 0.12);
      border: 1px solid rgba(232, 56, 248, 0.4);
      color: #7a006b;
    }

    
    .upload-card {
      background: rgba(255, 255, 255, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 16px;
      border-top: 3px solid purple;
      padding: 2rem;
      backdrop-filter: blur(6px);
      margin-bottom: 3rem;
      transition: 0.3s;
    }

    .upload-card:hover {
      background: rgba(255, 255, 255, 0.6);
    }

    .upload-card-label {
      font-size: .8rem;
      color: #2ca8dd;
      letter-spacing: .1em;
      text-transform: uppercase;
      margin-bottom: 1.5rem;
      font-weight: 700;
    }

    .upload-form { display: flex; flex-direction: column; gap: 1.25rem; }
    .form-group { display: flex; flex-direction: column; gap: .5rem; }

    .form-group label {
      font-size: .78rem;
      color: #555;
      letter-spacing: .06em;
      text-transform: uppercase;
      font-weight: 600;
    }

    .form-group input[type="file"] {
      background: rgba(255,255,255,0.5);
      border: 1px solid rgba(128, 0, 128, 0.25);
      border-radius: 10px;
      padding: .75rem 1rem;
      color: #0a121c;
      font-size: .85rem;
      outline: none;
      cursor: pointer;
      width: 100%;
      transition: border-color 0.3s;
    }

    .form-group input[type="file"]:hover {
      border-color: purple;
    }

    .form-group input[type="file"]::-webkit-file-upload-button {
      background: purple;
      border: none;
      border-radius: 6px;
      color: white;
      font-size: .78rem;
      padding: .35rem .9rem;
      cursor: pointer;
      margin-right: .85rem;
      font-weight: 600;
    }

    .form-group textarea {
      background: rgba(255,255,255,0.5);
      border: 1px solid rgba(128, 0, 128, 0.25);
      border-radius: 10px;
      padding: .85rem 1rem;
      color: #0a121c;
      font-size: .95rem;
      outline: none;
      resize: vertical;
      min-height: 100px;
      transition: border-color 0.3s, box-shadow 0.3s;
    }

    .form-group textarea:focus {
      border-color: purple;
      box-shadow: 0 0 0 3px rgba(128, 0, 128, 0.12);
    }

    .form-group textarea::placeholder { color: #888; opacity: .8; }

    
    .radio-group {
      display: flex;
      gap: 1.5rem;
      align-items: center;
      flex-wrap: wrap;
    }

    .radio-group label {
      display: flex;
      align-items: center;
      gap: .5rem;
      color: #0a121c;
      font-size: .85rem;
      letter-spacing: .04em;
      text-transform: uppercase;
      cursor: pointer;
      font-weight: 600;
    }

    .radio-group input[type="radio"] {
      accent-color: purple;
      cursor: pointer;
    }

    
    .btn-submit {
      align-self: flex-start;
      padding: 10px 25px;
      border-radius: 10px;
      font-size: .95rem;
      font-weight: bold;
      cursor: pointer;
      border: 1px solid purple;
      background: transparent;
      color: purple;
      transition: 0.3s;
    }

    .btn-submit:hover {
      background: purple;
      color: white;
    }

    .btn-submit:disabled { opacity: .5; cursor: not-allowed; }

    
    .gallery-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.5rem;
    }

    .gallery-title {
      font-size: .85rem;
      color: purple;
      letter-spacing: .1em;
      text-transform: uppercase;
      font-weight: 700;
    }

    .gallery-count {
      font-size: .78rem;
      color: #555;
      font-weight: 600;
    }

    .gallery-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25rem;
    }

    
    .photo-card {
      background: rgba(255, 255, 255, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 16px;
      overflow: hidden;
      backdrop-filter: blur(6px);
      transition: border-color 0.3s, transform 0.3s, box-shadow 0.3s;
      position: relative;
      border-top: 3px solid rgba(128, 0, 128, 0.4);
    }

    .photo-card:hover {
      border-color: purple;
      transform: translateY(-5px);
      box-shadow: 0 20px 40px -15px rgba(128, 0, 128, 0.2);
      background: rgba(255, 255, 255, 0.65);
    }

    .photo-img-wrap {
      width: 100%;
      aspect-ratio: 1 / 1;
      overflow: hidden;
      background: rgba(255,255,255,0.3);
    }

    .photo-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
      transition: transform .4s ease;
    }

    .photo-card:hover .photo-img-wrap img { transform: scale(1.05); }

    .photo-info { padding: .9rem 1rem 1.1rem; }

    .photo-time {
      font-size: .7rem;
      color: #666;
      margin-bottom: .4rem;
    }

    .photo-desc {
      font-size: .82rem;
      color: #333;
      line-height: 1.5;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      overflow: hidden;
    }

    .photo-badge {
      display: inline-block;
      font-size: .68rem;
      letter-spacing: .06em;
      text-transform: uppercase;
      padding: .2rem .6rem;
      border-radius: 6px;
      margin-bottom: .4rem;
      font-weight: 700;
    }

    .badge-public {
      background: rgba(56, 191, 248, 0.15);
      color: #0a6ea8;
      border: 1px solid rgba(56, 191, 248, 0.4);
    }

    .badge-private {
      background: rgba(232, 56, 248, 0.12);
      color: #7a006b;
      border: 1px solid rgba(232, 56, 248, 0.3);
    }

    .gallery-empty {
      grid-column: 1 / -1;
      text-align: center;
      padding: 4rem 2rem;
      border: 2px dashed rgba(128, 0, 128, 0.3);
      border-radius: 16px;
      color: #555;
      font-size: .9rem;
      background: rgba(255,255,255,0.3);
    }

    .gallery-loading {
      grid-column: 1 / -1;
      text-align: center;
      padding: 3rem;
      font-size: .85rem;
      color: #555;
    }

    
    footer {
      width: 500px;
      border-radius: 20px;
      text-align: center;
      padding: 15px 20px;
      background: purple;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 10px auto 20px;
    }

    footer ul {
      display: flex;
      list-style: none;
      gap: 90px;
      align-items: center;
      justify-content: center;
      font-size: .9rem;
    }

    @media (max-width: 1024px) { .gallery-grid { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) {
      .gallery-grid { grid-template-columns: repeat(2, 1fr); }
      .nav-links a:not(.active):not(.logout) { display: none; }
      footer { width: 90%; }
      footer ul { gap: 20px; flex-direction: column; }
    }
    @media (max-width: 480px) { .gallery-grid { grid-template-columns: 1fr; } }
  </style>
</head>
<body>


<header>
  <nav>
    <div class="logo"><?php echo $_SESSION['user']; ?> Gallery</div>
    <ul class="nav-links">
        <li><a href="dashboard.php">Dashboard</a></li>
      <li><a href="tutorial.php">My Tutorial</a></li>
      <li><a href="gallery.php" class="active">Gallery</a></li>
    </ul>
  </nav>
</header>

<main>

  <p class="section-label">// Photo Gallery</p>
  <h1 class="section-title">Upload &amp; <span>Share</span></h1>

  
  <div id="alert-box" class="alert"></div>

  
  <div class="upload-card">
    <p class="upload-card-label">// Add New Photo</p>

    <form id="upload-form" class="upload-form" enctype="multipart/form-data">

      <div class="form-group">
        <label for="photo">Choose Image</label>
        <input type="file" name="photo" id="photo" accept="image/*" required />
      </div>

      <div class="form-group">
        <label for="desc">Description</label>
        <textarea name="desc" id="desc" placeholder="Write something about this photo…"></textarea>
      </div>

      <div class="form-group">
        <label>Visibility</label>
        <div class="radio-group">
          <label>
            <input type="radio" name="valid" value="Public" checked />
            Public
          </label>
          <label>
            <input type="radio" name="valid" value="Private" />
            Private
          </label>
        </div>
      </div>

      <button type="submit" class="btn-submit" id="submit-btn">Post to Gallery</button>

    </form>
  </div>

  
  <div class="gallery-header">
    <span class="gallery-title">// Gallery</span>
    <span class="gallery-count" id="photo-count">Loading…</span>
  </div>

  <div class="gallery-grid" id="gallery-grid">
    <div class="gallery-loading">Fetching photos…</div>
  </div>

</main>


<footer>
  <ul>
    <li>hassanqadri047@gmail.com</li>
    <li>Nankana Sahib</li>
  </ul>
</footer>

<script>
$(document).ready(function () {

  
  function loadGallery() {
    $.ajax({
      url: "get_photos.php",
      type: "GET",
      dataType: "json",
      success: function (photos) {
        renderGallery(photos);
      },
      error: function () {
        $("#gallery-grid").html('<div class="gallery-empty">Failed to load photos.</div>');
        $("#photo-count").text("0 photos");
      }
    });
  }

  function renderGallery(photos) {
    var $grid = $("#gallery-grid");
    $grid.empty();

    if (!photos || photos.length === 0) {
      $grid.html('<div class="gallery-empty">No photos yet — upload one above!</div>');
      $("#photo-count").text("0 photos");
      return;
    }

    $("#photo-count").text(photos.length + (photos.length === 1 ? " photo" : " photos"));

    $.each(photos, function (i, row) {
      
      var desc        = row.description ? row.description : "No description.";
      
      var badgeClass  = row.valid === "Private" ? "badge-private" : "badge-public";
      
      var imageSrc    = "image/" + row.file;
      
      var uploadTime  = row.upload_time ? row.upload_time : "";

      var card =
        '<div class="photo-card">' +
          '<div class="photo-img-wrap">' +
            '<img src="' + imageSrc + '" alt="gallery photo" loading="lazy" />' +
          '</div>' +
          '<div class="photo-info">' +
            '<span class="photo-badge ' + badgeClass + '">' + row.valid + '</span>' +
            '<div class="photo-time">' + uploadTime + '</div>' +
            '<p class="photo-desc">' + desc + '</p>' +
          '</div>' +
        '</div>';

      $grid.append(card);
    });
  }

  
  $("#upload-form").on("submit", function (e) {
    e.preventDefault();

    var formData = new FormData(this);
    var $btn = $("#submit-btn");

    $btn.prop("disabled", true).text("Uploading…");
    hideAlert();

    $.ajax({
      url: "upload_pic.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (response) {
        if (response.success) {
          showAlert("success", response.message || "Photo uploaded successfully!");
          $("#upload-form")[0].reset();
          loadGallery();
        } else {
          showAlert("error", response.message || "Upload failed. Please try again.");
        }
      },
      error: function () {
        showAlert("error", "Server error. Please try again.");
      },
      complete: function () {
        $btn.prop("disabled", false).text("Post to Gallery");
      }
    });
  });

  
  function showAlert(type, msg) {
    var $box = $("#alert-box");
    $box.removeClass("alert-success alert-error")
        .addClass(type === "success" ? "alert-success" : "alert-error")
        .text(msg)
        .fadeIn(300);

    setTimeout(hideAlert, 4000);
  }

  function hideAlert() {
    $("#alert-box").fadeOut(300);
  }

  
  loadGallery();

});
</script>

</body>
</html>