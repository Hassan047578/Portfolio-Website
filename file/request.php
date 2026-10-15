<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include '../config.php';  
$current_id = (int)$_SESSION['user_id'];


if (isset($_POST['accept_id'])) {
    $req_id = (int)$_POST['accept_id'];
    $stmt = mysqli_prepare($conn,
        "UPDATE user_connect SET status = 'accepted'
         WHERE ID = ? AND recever_id = ? AND status = 'pending'"
    );
    mysqli_stmt_bind_param($stmt, "ii", $req_id, $current_id);
    mysqli_stmt_execute($stmt);
    header("Location: request.php");
    exit;
}


if (isset($_POST['reject_id'])) {
    $req_id = (int)$_POST['reject_id'];
    $stmt = mysqli_prepare($conn,
        "DELETE FROM user_connect
         WHERE ID = ? AND recever_id = ? AND status = 'pending'"
    );
    mysqli_stmt_bind_param($stmt, "ii", $req_id, $current_id);
    mysqli_stmt_execute($stmt);
    header("Location: request.php");
    exit;
}


$stmt = mysqli_prepare($conn,
    "SELECT uc.ID, uc.sender_id, u.name, u.email
     FROM user_connect AS uc
     JOIN users AS u ON uc.sender_id = u.id
     WHERE uc.recever_id = ? AND uc.status = 'pending'
     ORDER BY uc.ID DESC"
);
mysqli_stmt_bind_param($stmt, "i", $current_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$requests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Friend Requests</title>
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

    /* badge showing count */
    .request-count {
      display: inline-block;
      background: purple;
      color: white;
      border-radius: 20px;
      padding: 4px 14px;
      font-size: 14px;
      font-weight: 600;
      margin-top: 10px;
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

    /* ── CARDS ── */
    .requests-grid {
      display: flex;
      flex-direction: column;
      gap: 14px;
    }

    .request-card {
      background: rgba(255,255,255,0.6);
      border: 1px solid rgba(128,0,128,0.18);
      border-top: 3px solid purple;
      border-radius: 12px;
      padding: 18px 24px;
      display: flex;
      align-items: center;
      gap: 16px;
      transition: 0.3s;
      backdrop-filter: blur(6px);
    }
    .request-card:hover {
      background: rgba(255,255,255,0.85);
      border-color: purple;
      transform: translateY(-2px);
      box-shadow: 0 4px 18px rgba(128,0,128,0.15);
    }

    .avatar {
      width: 54px;
      height: 54px;
      border-radius: 50%;
      background: purple;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.4rem;
      font-weight: 700;
      flex-shrink: 0;
      border: 2px solid rgba(255,255,255,0.6);
      text-transform: uppercase;
    }

    .request-info { flex: 1; }
    .request-name {
      font-size: 1rem;
      font-weight: 700;
      color: #0a121c;
      margin-bottom: 3px;
    }
    .request-email { font-size: 13px; color: #555; }

    .request-actions {
      display: flex;
      gap: 10px;
      flex-shrink: 0;
    }

    .btn-accept, .btn-reject {
      padding: 8px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 14px;
      cursor: pointer;
      transition: 0.3s;
    }

    .btn-accept {
      background: transparent;
      border: 2px solid purple;
      color: purple;
    }
    .btn-accept:hover { background: purple; color: white; }

    .btn-reject {
      background: transparent;
      border: 2px solid #e838f8;
      color: #e838f8;
    }
    .btn-reject:hover { background: #e838f8; color: white; }

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
      .request-card { flex-direction: column; text-align: center; }
      .request-actions { justify-content: center; }
      footer { width: 90%; }
      .page-header h1 { font-size: 32px; }
      nav ul li:not(:last-child) { display: none; }
    }
  </style>
</head>
<body>

<header>
  <nav>
    <div class="nav-logo">Friend <span>Requests</span></div>
    <ul>
      <li><a href="../dashboard.php">Dashboard</a></li>
      <li><a href="add_friend.php">Add Friend</a></li>
      <li><a href="show_friend.php">Show Friend</a></li>
      <li><a href="request.php" class="active">Requests</a></li>
      <li><a href="../logout.php" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="page-header">
    <h1>Pending <span>Requests</span></h1>
    <p>Accept or reject incoming friend requests below.</p>
    <?php if (!empty($requests)): ?>
      <span class="request-count"><?php echo count($requests); ?> pending</span>
    <?php endif; ?>
  </div>

  <?php if (empty($requests)): ?>
    <div class="empty-state">No pending friend requests at the moment.</div>

  <?php else: ?>
    <div class="requests-grid">
      <?php foreach ($requests as $row): ?>
      <div class="request-card">

        <!-- Avatar -->
        <div class="avatar">
          <?php echo htmlspecialchars(substr($row['name'], 0, 1)); ?>
        </div>

        <!-- Info -->
        <div class="request-info">
          <div class="request-name"><?php echo htmlspecialchars($row['name']); ?></div>
          <div class="request-email">✉ <?php echo htmlspecialchars($row['email']); ?></div>
        </div>

        <!-- Accept / Reject -->
        <div class="request-actions">
          <form method="POST">
            <input type="hidden" name="accept_id" value="<?php echo (int)$row['ID']; ?>">
            <button type="submit" class="btn-accept">✓ Accept</button>
          </form>
          <form method="POST">
            <input type="hidden" name="reject_id" value="<?php echo (int)$row['ID']; ?>">
            <button type="submit" class="btn-reject">✕ Reject</button>
          </form>
        </div>

      </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>

<footer>
  <ul>
    <li>hassanqadri047@gmail.com</li>
    <li>Nankana Sahib</li>
  </ul>
</footer>

</body>
</html>