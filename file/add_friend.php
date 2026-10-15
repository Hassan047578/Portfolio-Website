<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include "../config.php";   
$current_id = (int)$_SESSION['user_id'];


if (isset($_GET['req_id'])) {
    $rec_id = (int)$_GET['req_id'];

    if ($rec_id === $current_id) {
        header("Location: add_friend.php");
        exit;
    }

    $check = mysqli_prepare($conn,
        "SELECT ID FROM user_connect
         WHERE ((sender_id = ? AND recever_id = ?)
             OR (sender_id = ? AND recever_id = ?))
           AND status = 'pending'"
    );
    mysqli_stmt_bind_param($check, "iiii", $current_id, $rec_id, $rec_id, $current_id);
    mysqli_stmt_execute($check);
    mysqli_stmt_store_result($check);

    $checkFriend = mysqli_prepare($conn,
        "SELECT ID FROM user_connect
         WHERE ((sender_id = ? AND recever_id = ?)
             OR (sender_id = ? AND recever_id = ?))
           AND status = 'accepted'"
    );
    mysqli_stmt_bind_param($checkFriend, "iiii", $current_id, $rec_id, $rec_id, $current_id);
    mysqli_stmt_execute($checkFriend);
    mysqli_stmt_store_result($checkFriend);

    if (mysqli_stmt_num_rows($check) === 0 && mysqli_stmt_num_rows($checkFriend) === 0) {
        $insert = mysqli_prepare($conn,
            "INSERT INTO user_connect (sender_id, recever_id, status) VALUES (?, ?, 'pending')"
        );
        mysqli_stmt_bind_param($insert, "ii", $current_id, $rec_id);
        mysqli_stmt_execute($insert);
    }

    header("Location: add_friend.php");
    exit;
}

$query = mysqli_prepare($conn,
    "SELECT u.id, u.name, u.email,
     (SELECT status FROM user_connect
      WHERE (sender_id = ? AND recever_id = u.id)
         OR (sender_id = u.id AND recever_id = ?)
      ORDER BY ID DESC LIMIT 1) AS connect_status
     FROM users u
     WHERE u.id != ?"
);
mysqli_stmt_bind_param($query, "iii", $current_id, $current_id, $current_id);
mysqli_stmt_execute($query);
$result = mysqli_stmt_get_result($query);

$rows = [];
while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Add Friend</title>
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

    /* ── SEARCH ── */
    .search-wrap {
      margin-bottom: 20px;
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

    /* ── TABLE WRAPPER ── */
    .table-wrapper {
      background: rgba(255,255,255,0.6);
      border-radius: 14px;
      border: 1px solid rgba(128,0,128,0.18);
      overflow: hidden;
      backdrop-filter: blur(6px);
      box-shadow: 0 4px 20px rgba(128,0,128,0.08);
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    /* ── TABLE HEAD ── */
    thead tr {
      background: purple;
      color: white;
    }
    thead th {
      padding: 14px 20px;
      text-align: left;
      font-size: 14px;
      font-weight: 700;
      letter-spacing: 0.04em;
      text-transform: uppercase;
    }
    thead th:first-child { width: 60px; text-align: center; }
    thead th:last-child  { text-align: center; width: 200px; }

    /* ── TABLE BODY ── */
    tbody tr {
      border-bottom: 1px solid rgba(128,0,128,0.1);
      transition: background 0.2s;
    }
    tbody tr:last-child { border-bottom: none; }
    tbody tr:hover { background: rgba(255,255,255,0.75); }
    tbody tr.hidden { display: none; }

    tbody td {
      padding: 14px 20px;
      font-size: 14px;
      color: #0a121c;
      vertical-align: middle;
    }
    tbody td:first-child { text-align: center; color: #777; font-weight: 600; }
    tbody td:last-child  { text-align: center; }

    /* avatar + name in same cell */
    .name-cell {
      display: flex;
      align-items: center;
      gap: 12px;
    }
    .avatar {
      width: 38px;
      height: 38px;
      border-radius: 50%;
      background: purple;
      color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      font-weight: 700;
      flex-shrink: 0;
      text-transform: uppercase;
    }

    /* ── ACTION BUTTONS ── */
    .btn-add {
      padding: 7px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
      border: 2px solid purple;
      background: transparent;
      color: purple;
      transition: 0.3s;
      text-decoration: none;
      display: inline-block;
    }
    .btn-add:hover { background: purple; color: white; }

    .btn-pending {
      padding: 7px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      border: 2px solid #aaa;
      background: transparent;
      color: #888;
      cursor: default;
      display: inline-block;
    }

    .btn-friends {
      padding: 7px 16px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      border: 2px solid green;
      background: transparent;
      color: green;
      cursor: default;
      display: inline-block;
    }

    .accepted-wrap {
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }

    .btn-chat {
      padding: 7px 14px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 13px;
      border: 2px solid purple;
      background: purple;
      color: white;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: 0.3s;
    }
    .btn-chat:hover { background: #5a005a; border-color: #5a005a; }

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

    /* no-results row inside table */
    .no-results-row td {
      text-align: center;
      color: #888;
      padding: 30px;
      font-size: 14px;
    }

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
      footer { width: 90%; }
      .page-header h1 { font-size: 30px; }
      nav ul li:not(:last-child) { display: none; }
      thead th:nth-child(3) { display: none; }
      tbody td:nth-child(3) { display: none; }
    }
  </style>
</head>
<body>

<header>
  <nav>
    <div class="nav-logo">Add <span>Friends</span></div>
    <ul>
      <li><a href="../dashboard.php">Dashboard</a></li>
      <li><a href="add_friend.php" class="active">Add Friend</a></li>
      <li><a href="request.php">Requests</a></li>
      <li><a href="show_friend.php">My Friends</a></li>
      <li><a href="../logout.php" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
</header>

<main>
  <div class="page-header">
    <h1>People You May <span>Know</span></h1>
    <p>Send a friend request to connect with others.</p>
  </div>

  <?php if (empty($rows)): ?>
    <div class="empty-state">No other users found.</div>
  <?php else: ?>

    <!-- Search -->
    <div class="search-wrap">
      <span class="search-icon">⌕</span>
      <input type="text" id="search-input" placeholder="Search by name or email…" autocomplete="off">
    </div>

    <!-- Table -->
    <div class="table-wrapper">
      <table id="users-table">
        <thead>
          <tr>
            <th>#</th>
            <th>Name</th>
            <th>Email</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="table-body">
          <?php foreach ($rows as $index => $row): ?>
          <tr data-name="<?php echo strtolower(htmlspecialchars($row['name'])); ?>"
              data-email="<?php echo strtolower(htmlspecialchars($row['email'])); ?>">

            <!-- # -->
            <td><?php echo $index + 1; ?></td>

            <!-- Name with avatar -->
            <td>
              <div class="name-cell">
                <div class="avatar">
                  <?php echo htmlspecialchars(substr($row['name'], 0, 1)); ?>
                </div>
                <?php echo htmlspecialchars($row['name']); ?>
              </div>
            </td>

            <!-- Email -->
            <td>✉ <?php echo htmlspecialchars($row['email']); ?></td>

            <!-- Action -->
            <td>
              <?php $status = $row['connect_status'] ?? null; ?>

              <?php if ($status === 'accepted'): ?>
                <div class="accepted-wrap">
                  <span class="btn-friends">✓ Friends</span>
                  <a href="chat.php?with=<?php echo (int)$row['id']; ?>" class="btn-chat">💬 Chat</a>
                </div>

              <?php elseif ($status === 'pending'): ?>
                <span class="btn-pending">⏳ Pending…</span>

              <?php else: ?>
                <a href="add_friend.php?req_id=<?php echo (int)$row['id']; ?>" class="btn-add">
                  + Add Friend
                </a>
              <?php endif; ?>
            </td>

          </tr>
          <?php endforeach; ?>

          <!-- No results row (shown by JS) -->
          <tr id="no-results-row" style="display:none;" class="no-results-row">
            <td colspan="4">No users match your search.</td>
          </tr>
        </tbody>
      </table>
    </div>

  <?php endif; ?>
</main>

<footer>
  <ul>
    <li>hassanqadri047@gmail.com</li>
    <li>Nankana Sahib</li>
  </ul>
</footer>

<script>
  const input      = document.getElementById('search-input');
  const rows       = document.querySelectorAll('#table-body tr[data-name]');
  const noResults  = document.getElementById('no-results-row');

  input?.addEventListener('input', () => {
    const q = input.value.toLowerCase().trim();
    let visible = 0;
    rows.forEach(row => {
      const match = !q
        || (row.dataset.name  || '').includes(q)
        || (row.dataset.email || '').includes(q);
      row.classList.toggle('hidden', !match);
      if (match) visible++;
    });
    if (noResults) noResults.style.display = visible === 0 ? 'table-row' : 'none';
  });
</script>
</body>
</html>