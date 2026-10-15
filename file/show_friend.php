<?php
session_start();

// ── GUARD ────────────────────────────────────────────────────────────────────
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include '../config.php';          // $conn  (matches login backend)
$current_id = (int)$_SESSION['user_id'];

// ── FETCH ACCEPTED FRIENDS ────────────────────────────────────────────────────
$stmt = mysqli_prepare($conn,
    "SELECT
        u.id, u.name, u.email,
        uc.ID AS connect_id
     FROM user_connect AS uc
     JOIN users AS u
       ON u.id = IF(uc.sender_id = ?, uc.recever_id, uc.sender_id)
     WHERE (uc.sender_id = ? OR uc.recever_id = ?)
       AND uc.status = 'accepted'"
);
mysqli_stmt_bind_param($stmt, "iii", $current_id, $current_id, $current_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$friends = [];
while ($row = mysqli_fetch_assoc($result)) {
    $friends[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>My Friends</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', sans-serif;
    }

    body {
      background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
      color: #0a121c;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    /* ── NAV ── */
    header {
      position: sticky;
      top: 0;
      backdrop-filter: blur(10px);
      background: rgba(255,255,255,0.3);
      border-bottom: 1px solid rgba(255,255,255,0.08);
      z-index: 1000;
    }

    nav {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 5vw;
      font-size: large;
    }

    .nav-logo {
      font-size: 1.4rem;
      font-weight: 700;
      color: purple;
    }
    .nav-logo span { color: #0a121c; }

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
    nav ul li a:hover { color: #2ca8dd; }
    nav ul li a.active { color: purple; font-weight: 700; }

    .logout-btn {
      padding: 8px 14px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      border: 1px solid #e838f8;
      color: #e838f8 !important;
      transition: 0.3s;
    }
    .logout-btn:hover {
      background: #e838f8;
      color: white !important;
    }

    /* ── MAIN ── */
    main {
      flex: 1;
      padding: 40px 5vw;
      max-width: 1100px;
      width: 100%;
      margin: 0 auto;
    }

    /* ── PAGE HEADER ── */
    .page-header {
      text-align: center;
      margin-bottom: 30px;
    }
    .page-header h1 {
      font-size: 46px;
      font-weight: 700;
      color: #0a121c;
    }
    .page-header h1 span { color: purple; }
    .page-header p {
      color: #333;
      margin-top: 8px;
      font-size: 15px;
    }

    .count-badge {
      display: inline-block;
      background: purple;
      color: white;
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 14px;
      font-weight: 600;
      margin-top: 10px;
    }

    /* ── SEARCH ── */
    .search-wrap {
      margin-bottom: 24px;
      position: relative;
    }
    .search-wrap input {
      width: 100%;
      padding: 12px 20px 12px 44px;
      border-radius: 10px;
      border: 1px solid rgba(128,0,128,0.35);
      background: rgba(255,255,255,0.55);
      font-size: 15px;
      color: #0a121c;
      outline: none;
      transition: 0.3s;
    }
    .search-wrap input:focus {
      border-color: purple;
      background: rgba(255,255,255,0.85);
    }
    .search-wrap input::placeholder { color: #888; }
    .search-icon {
      position: absolute;
      left: 14px;
      top: 50%;
      transform: translateY(-50%);
      color: purple;
      font-size: 18px;
      pointer-events: none;
    }

    /* ── EMPTY STATE ── */
    .empty-state {
      background: rgba(255,255,255,0.5);
      border: 1px dashed purple;
      border-radius: 12px;
      padding: 40px;
      text-align: center;
      color: #555;
      font-size: 15px;
    }
    .empty-icon { font-size: 2.5rem; margin-bottom: 12px; }
    .empty-title { font-size: 16px; font-weight: 600; margin-bottom: 6px; color: #333; }
    .empty-sub   { font-size: 13px; color: #777; margin-bottom: 16px; }

    .btn-go {
      display: inline-block;
      padding: 9px 20px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      border: 2px solid purple;
      color: purple;
      background: transparent;
      text-decoration: none;
      transition: 0.3s;
    }
    .btn-go:hover { background: purple; color: white; }

    /* ── FRIENDS GRID ── */
    .friends-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 16px;
    }

    .friend-card {
      background: rgba(255,255,255,0.6);
      border: 1px solid rgba(128,0,128,0.18);
      border-top: 3px solid purple;
      border-radius: 12px;
      padding: 24px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      transition: 0.3s;
      backdrop-filter: blur(6px);
      position: relative;
    }
    .friend-card:hover {
      background: rgba(255,255,255,0.85);
      border-color: purple;
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(128,0,128,0.15);
    }

    /* avatar with online dot */
    .avatar-wrap {
      position: relative;
      margin-bottom: 14px;
    }
    .avatar {
      width: 72px;
      height: 72px;
      border-radius: 50%;
      background: purple;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.8rem;
      font-weight: 700;
      border: 3px solid rgba(255,255,255,0.6);
      text-transform: uppercase;
    }
    .online-dot {
      position: absolute;
      bottom: 3px;
      right: 3px;
      width: 14px;
      height: 14px;
      border-radius: 50%;
      background: #22c55e;
      border: 2px solid white;
      box-shadow: 0 0 6px rgba(34,197,94,0.6);
    }

    .friend-name {
      font-size: 1rem;
      font-weight: 700;
      color: #0a121c;
      margin-bottom: 4px;
    }
    .friend-email {
      font-size: 13px;
      color: #555;
      margin-bottom: 14px;
    }

    .friends-tag {
      display: inline-block;
      background: transparent;
      border: 2px solid green;
      color: green;
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 13px;
      font-weight: 600;
    }

    .friend-card.hidden { display: none; }

    /* ── FOOTER ── */
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
      margin: 20px auto;
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
    footer ul li { font-size: 14px; }

    @media (max-width: 768px) {
      .friends-grid { grid-template-columns: 1fr; }
      footer { width: 90%; }
      .page-header h1 { font-size: 32px; }
      nav ul li:not(:last-child) { display: none; }
    }
  </style>
</head>
<body>

<header>
  <nav>
    <div class="nav-logo">My <span>Friends</span></div>
    <ul>
      <li><a href="../dashboard.php">Dashboard</a></li>
      <li><a href="add_friend.php">Add Friend</a></li>
      <li><a href="request.php">Requests</a></li>
      <li><a href="show_friend.php" class="active">My Friends</a></li>
      <li><a href="../logout.php" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="page-header">
    <h1>My <span>Friends</span></h1>
    <p>All your accepted connections in one place.</p>
    <span class="count-badge">
      <?php echo count($friends); ?> friend<?php echo count($friends) !== 1 ? 's' : ''; ?>
    </span>
  </div>

  <?php if (empty($friends)): ?>
    <!-- Empty state -->
    <div class="empty-state">
      <div class="empty-icon">👥</div>
      <div class="empty-title">No friends yet.</div>
      <div class="empty-sub">Send some requests and start connecting.</div>
      <a href="add_friend.php" class="btn-go">+ Find People</a>
    </div>

  <?php else: ?>

    <!-- Search -->
    <div class="search-wrap">
      <span class="search-icon">⌕</span>
      <input type="text" id="search-input" placeholder="Search friends by name or email…" autocomplete="off">
    </div>

    <!-- Grid -->
    <div class="friends-grid" id="friends-grid">
      <?php foreach ($friends as $friend): ?>
      <div class="friend-card"
           data-name="<?php echo strtolower(htmlspecialchars($friend['name'])); ?>"
           data-email="<?php echo strtolower(htmlspecialchars($friend['email'])); ?>">

        <!-- Avatar with online dot -->
        <div class="avatar-wrap">
          <div class="avatar">
            <?php echo htmlspecialchars(substr($friend['name'], 0, 1)); ?>
          </div>
          <div class="online-dot"></div>
        </div>

        <!-- Info -->
        <div class="friend-name"><?php echo htmlspecialchars($friend['name']); ?></div>
        <div class="friend-email">✉ <?php echo htmlspecialchars($friend['email']); ?></div>

        <span class="friends-tag">✓ Friends</span>

      </div>
      <?php endforeach; ?>
    </div>

    <div class="empty-state" id="no-results" style="display:none; margin-top:16px;">
      <div class="empty-title">No friends match your search.</div>
    </div>

  <?php endif; ?>
</main>

<footer>
 <footer>
  <ul>
    <li>hassanqadri047@gmail.com</li>
    <li>Nankana Sahib</li>
  </ul>
</footer>

<script>
  const input     = document.getElementById('search-input');
  const cards     = document.querySelectorAll('.friend-card');
  const noResults = document.getElementById('no-results');

  input?.addEventListener('input', () => {
    const q = input.value.toLowerCase().trim();
    let visible = 0;
    cards.forEach(card => {
      const match = !q
        || (card.dataset.name  || '').includes(q)
        || (card.dataset.email || '').includes(q);
      card.classList.toggle('hidden', !match);
      if (match) visible++;
    });
    if (noResults) noResults.style.display = visible === 0 ? 'block' : 'none';
  });
</script>

</body>
</html>