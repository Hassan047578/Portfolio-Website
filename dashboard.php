<?php
  session_start(); 
  include "config.php"; 
  include "authentication.php";
  ?>
  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Home</title>

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
      color: #ff13e0;
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
      max-width: 1100px;
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
      margin-bottom: 2.5rem;
      color: #0a121c;
    }

    .section-title span { color: purple; }

    
    .profile-card {
      background: rgba(255, 255, 255, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 16px;
      padding: 2.5rem;
      display: flex;
      gap: 2.5rem;
      align-items: center;
      margin-bottom: 2rem;
      backdrop-filter: blur(6px);
      border-top: 3px solid purple;
      transition: 0.3s;
    }

    .profile-card:hover { transform: translateY(-3px); }

    
    .avatar-wrap { position: relative; flex-shrink: 0; }

    .avatar {
      width: 120px; height: 120px; border-radius: 50%;
      object-fit: cover;
      border: 3px solid purple;
      box-shadow: 0 0 0 6px rgba(128, 0, 128, 0.15);
      display: block;
    }

    .avatar-fallback {
      width: 120px; height: 120px; border-radius: 50%;
      background: rgba(232, 56, 248, 0.1);
      border: 3px solid purple;
      box-shadow: 0 0 0 6px rgba(128, 0, 128, 0.15);
      display: flex; align-items: center; justify-content: center;
      font-size: 2.5rem; font-weight: 800; color: purple;
    }

    .online-dot {
      position: absolute; bottom: 6px; right: 6px;
      width: 14px; height: 14px; border-radius: 50%;
      background: #38bdf8;
      border: 2px solid white;
    }

    
    .profile-info { flex: 1; }

    .profile-greeting {
      font-size: .8rem;
      color: #2ca8dd;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: .4rem;
      font-weight: 600;
    }

    .profile-name {
      font-size: 2rem; font-weight: 800;
      margin-bottom: .25rem; line-height: 1.1;
      color: #0a121c;
    }

    .profile-name span { color: purple; }

    .profile-email {
      font-size: .85rem;
      color: #555;
      margin-bottom: 1.25rem;
    }

    
    .info-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1.25rem;
      margin-bottom: 2rem;
    }

    .info-card {
      background: rgba(255, 255, 255, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 16px;
      padding: 1.5rem;
      backdrop-filter: blur(6px);
      transition: 0.3s;
      position: relative;
      overflow: hidden;
    }

    .info-card:hover {
      border-color: purple;
      transform: translateY(-4px);
      background: rgba(255, 255, 255, 0.6);
    }

    .info-card-label {
      font-size: .75rem;
      color: #555;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: .5rem;
      font-weight: 600;
    }

    .info-card-value {
      font-size: 1rem;
      font-weight: 700;
      color: #0a121c;
      word-break: break-all;
    }

    .info-card-icon {
      position: absolute; bottom: 1rem; right: 1rem;
      font-size: 1.4rem; opacity: .2;
    }

    
    .quick-title {
      font-size: .85rem;
      color: purple;
      letter-spacing: .08em;
      text-transform: uppercase;
      margin-bottom: 1rem;
      font-weight: 700;
    }

    .quick-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }

    .quick-card {
      background: rgba(255, 255, 255, 0.45);
      border: 1px solid rgba(255, 255, 255, 0.6);
      border-radius: 16px;
      padding: 1.4rem 1.5rem;
      display: flex;
      align-items: center;
      gap: 1rem;
      backdrop-filter: blur(6px);
      transition: 0.3s;
      text-decoration: none;
      color: inherit;
    }

    .quick-card:hover {
      border-color: purple;
      transform: translateY(-4px);
      background: rgba(255, 255, 255, 0.65);
    }

    .quick-icon { font-size: 1.5rem; flex-shrink: 0; }

    .quick-label {
      font-size: .9rem;
      font-weight: 700;
      color: #0a121c;
    }

    .quick-desc {
      font-size: .78rem;
      color: #555;
      margin-top: .15rem;
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

    @media (max-width: 768px) {
      .profile-card { flex-direction: column; text-align: center; }
      .info-grid, .quick-grid { grid-template-columns: 1fr; }
      .nav-links a:not(.logout):not(.active) { display: none; }
      footer { width: 90%; }
      footer ul { gap: 20px; flex-direction: column; }
    }
  </style>
</head>
<body>


<header>
  <nav>
    <div class="logo"><?php echo htmlspecialchars($_SESSION['user']); ?> Dashboard</div>
    <ul class="nav-links">
      
      <li><a href="gallery.php">Gallery</a></li>
      <li><a href="file/add_friend.php">Friends</a></li>
      <!--<li><a href="file/show_friend.php">Show Friends</a></li>-->
      <li><a href="logout.php" class="logout">Logout</a></li>
    
    </ul>
  </nav>
</header>

<main>

  <p class="section-label"> Dashboard</p>
  <h1 class="section-title">Welcome <span>Back</span></h1>

  
  <div class="profile-card">

    <div class="avatar-wrap">
    <?php 
      $current_email = $_SESSION['email'];
      $query = mysqli_query($conn,"Select * from users where email='$current_email'");
      $row = mysqli_fetch_assoc($query);
     if($row){ ?>
      <!-- <img
        src="<?php //echo $row['gallery']; ?>"
        alt="Profile Picture"
        class="avatar"
        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
      /> -->
      <!-- Fallback initials shown if image not found -->
      <div class="avatar-fallback" style="display:flex;">
           <?php echo strtoupper(substr($row['name'],0,1))?>
      </div>
      <div class="online-dot"></div>
    </div>  
        <div class="profile-info">
      <p class="profile-greeting"> Logged in as</p>
      
      <h2 class="profile-name"><?php echo $row['name']; ?> </h2>
      <p class="profile-email">✉ <?php echo $row['email']; ?></p>
      <div class="profile-badges">
        
      </div>
    </div>

  </div>  
  <div class="info-grid">
    <div class="info-card">
      <div class="info-card-label">Full Name</div>
     
      <div class="info-card-value"><?php echo $row['name']; ?></div>
      <div class="info-card-icon">👤</div>
    </div>
    <div class="info-card">
      <div class="info-card-label">Email Address</div>
     
      <div class="info-card-value"><?php echo $row['email']; ?></div>
      <div class="info-card-icon">✉</div>
    </div>
    
  </div>  
  <?php }?>
    
    

  <p class="quick-title"> Quick Links</p>
  <div class="quick-grid">
    <a href="index.php" class="quick-card">
      <div class="quick-icon">🏠</div>
      <div>
        <div class="quick-label">Portfolio</div>
        <div class="quick-desc">View the main portfolio dashboard</div>
      </div>
    </a>
    <a href="tutorial.php" class="quick-card">
      <div class="quick-icon">🎬</div>
      <div>
        <div class="quick-label">Tutorials</div>
        <div class="quick-desc">Watch all 11 video episodes</div>
      </div>
    </a>
    <a href="gallery.php" class="quick-card">
      <div class="quick-icon">🚪</div>
      <div>
        <div class="quick-label">gallery</div>
        <div class="quick-desc">Save your Memories</div>
      </div>
    <a href="file/add_friend.php" class="quick-card">
      <div class="quick-icon">👥</div>
      <div>
        <div class="quick-label">Friends</div>
        <div class="quick-desc">Add  your friends</div>
      </div>
    </a>
  </div>

</main>


<footer>
  <ul>
    <li>hassanqadri047@gmail.com</li>
    <li>Nankana Sahib</li>
  </ul>
</footer>

</body>
</html>