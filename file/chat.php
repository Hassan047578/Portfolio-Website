<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit;
}

include '../config.php';
$current_id = (int)$_SESSION['user_id'];


if (!isset($_GET['with']) || !(int)$_GET['with']) {
    header("Location: add_friend.php");
    exit;
}
$other_id = (int)$_GET['with'];

$check = mysqli_prepare($conn,
    "SELECT ID FROM user_connect
     WHERE ((sender_id = ? AND recever_id = ?) OR (sender_id = ? AND recever_id = ?))
       AND status = 'accepted' LIMIT 1"
);
mysqli_stmt_bind_param($check, "iiii", $current_id, $other_id, $other_id, $current_id);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if (mysqli_stmt_num_rows($check) === 0) {
    header("Location: add_friend.php");
    exit;
}

$uStmt = mysqli_prepare($conn, "SELECT name, email FROM users WHERE id = ? LIMIT 1");
mysqli_stmt_bind_param($uStmt, "i", $other_id);
mysqli_stmt_execute($uStmt);
$uResult = mysqli_stmt_get_result($uStmt);
$other = mysqli_fetch_assoc($uResult);
if (!$other) { header("Location: add_friend.php"); exit; }


$chat_id = min($current_id, $other_id) . '_' . max($current_id, $other_id);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['text'])) {
    $text = trim($_POST['text']);
    if ($text !== '') {
        $ins = mysqli_prepare($conn,
            "INSERT INTO messages (chat_id, sender_id, text) VALUES (?, ?, ?)"
        );
        mysqli_stmt_bind_param($ins, "sis", $chat_id, $current_id, $text);
        mysqli_stmt_execute($ins);
    }
    header('Content-Type: application/json');
    echo json_encode(fetchMessages($conn, $chat_id));
    exit;
}

if (isset($_GET['poll'])) {
    header('Content-Type: application/json');
    echo json_encode(fetchMessages($conn, $chat_id));
    exit;
}

function fetchMessages($conn, $chat_id) {
    $stmt = mysqli_prepare($conn,
        "SELECT m.id, m.sender_id, m.text, m.date, u.name AS sender_name
         FROM messages m
         JOIN users u ON m.sender_id = u.id
         WHERE m.chat_id = ? ORDER BY m.date ASC"
    );
    mysqli_stmt_bind_param($stmt, "s", $chat_id);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);
    $msgs = [];
    while ($row = mysqli_fetch_assoc($res)) {
        $row['unix_ts'] = strtotime($row['date']);
        $msgs[] = $row;
    }
    return $msgs;
}

$messages = fetchMessages($conn, $chat_id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Chat with <?php echo htmlspecialchars($other['name']); ?></title>
  <style>
    *, *::before, *::after {
      margin: 0; padding: 0;
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
    .nav-logo { font-size: 1.4rem; font-weight: 700; color: purple; }
    .nav-logo span { color: #0a121c; }
    nav ul { display: flex; list-style: none; gap: 22px; align-items: center; }
    nav ul li a { color: #0a121c; text-decoration: none; font-weight: 500; transition: 0.3s; }
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
    .logout-btn:hover { background: #e838f8; color: white !important; }

    /* ── CHAT WRAPPER ── */
    main {
      flex: 1;
      display: flex;
      flex-direction: column;
      max-width: 780px;
      width: 100%;
      margin: 30px auto;
      padding: 0 16px 20px;
    }

    /* ── CHAT HEADER ── */
    .chat-header {
      background: rgba(255,255,255,0.75);
      border-radius: 14px 14px 0 0;
      padding: 16px 22px;
      display: flex;
      align-items: center;
      gap: 14px;
      border: 1px solid rgba(128,0,128,0.2);
      border-bottom: none;
      box-shadow: 0 2px 10px rgba(128,0,128,0.08);
      backdrop-filter: blur(6px);
    }
    .chat-avatar {
      width: 46px; height: 46px;
      border-radius: 50%;
      background: purple;
      color: white;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; font-weight: 700;
      text-transform: uppercase;
      flex-shrink: 0;
    }
    .chat-user-info .chat-name { font-weight: 700; font-size: 1rem; color: #0a121c; }
    .chat-user-info .chat-email { font-size: 12px; color: #7a5a9c; margin-top: 2px; }
    .back-btn {
      margin-left: auto;
      padding: 7px 14px;
      border-radius: 8px;
      font-size: 13px;
      font-weight: 600;
      border: 1px solid rgba(128,0,128,0.4);
      color: purple;
      text-decoration: none;
      transition: 0.3s;
      flex-shrink: 0;
    }
    .back-btn:hover { background: purple; color: white; }

    /* ── MESSAGE AREA ── */
    #chat-box {
      background: rgba(255,255,255,0.6);
      border: 1px solid rgba(128,0,128,0.18);
      border-top: none;
      border-bottom: none;
      padding: 20px 18px;
      overflow-y: auto;
      min-height: 420px;
      max-height: 55vh;
      display: flex;
      flex-direction: column;
      gap: 10px;
      scroll-behavior: smooth;
      backdrop-filter: blur(6px);
    }

    /* ── BUBBLES ── */
    .bubble-wrap { display: flex; flex-direction: column; }
    .bubble-wrap.mine   { align-items: flex-end; }
    .bubble-wrap.theirs { align-items: flex-start; }

    .bubble-sender {
      font-size: 11px;
      font-weight: 700;
      color: purple;
      margin-bottom: 3px;
      padding: 0 4px;
    }
    .mine .bubble-sender { color: #9a5a9c; }

    .bubble {
      max-width: 68%;
      padding: 10px 15px;
      border-radius: 18px;
      font-size: 14px;
      line-height: 1.5;
      word-break: break-word;
    }
    .mine .bubble {
      background: purple;
      color: white;
      border-bottom-right-radius: 4px;
    }
    .theirs .bubble {
      background: white;
      color: #0a121c;
      border: 1px solid rgba(128,0,128,0.2);
      border-bottom-left-radius: 4px;
    }
    .bubble-time {
      font-size: 11px;
      color: #9a8aad;
      margin-top: 3px;
      padding: 0 4px;
    }

    .no-msgs {
      text-align: center;
      color: #9a7aad;
      font-size: 14px;
      margin: auto;
    }

    /* ── INPUT BAR ── */
    .chat-input-bar {
      background: rgba(255,255,255,0.75);
      border: 1px solid rgba(128,0,128,0.2);
      border-top: 2px solid rgba(128,0,128,0.12);
      border-radius: 0 0 14px 14px;
      padding: 14px 16px;
      display: flex;
      gap: 10px;
      align-items: center;
      backdrop-filter: blur(6px);
    }
    #msg-input {
      flex: 1;
      padding: 10px 16px;
      border-radius: 24px;
      border: 1px solid rgba(128,0,128,0.3);
      font-size: 14px;
      color: #0a121c;
      outline: none;
      transition: 0.3s;
      background: rgba(255,255,255,0.6);
    }
    #msg-input:focus { border-color: purple; background: white; }
    #msg-input::placeholder { color: #aaa; }

    #send-btn {
      width: 44px; height: 44px;
      border-radius: 50%;
      background: purple;
      color: white;
      border: none;
      font-size: 18px;
      cursor: pointer;
      display: flex; align-items: center; justify-content: center;
      transition: 0.3s;
      flex-shrink: 0;
    }
    #send-btn:hover { background: #5a005a; }
    #send-btn:disabled { background: #c8a0c8; cursor: default; }

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
      margin: 10px auto 20px;
      overflow: hidden;
    }
    footer ul {
      display: flex; list-style: none; gap: 40px;
      align-items: center; justify-content: center; flex-wrap: wrap;
    }
    footer ul li { font-size: 14px; }

    @media (max-width: 600px) {
      .bubble { max-width: 85%; }
      footer { width: 90%; }
      nav ul li:not(:last-child) { display: none; }
      main { margin: 10px auto; }
    }
  </style>
</head>
<body>

<header>
  <nav>
    <div class="nav-logo">💬 <span>Chat</span></div>
    <ul>
      <li><a href="../dashboard.php">Dashboard</a></li>
      <li><a href="add_friend.php">Friends</a></li>
      <li><a href="request.php">Requests</a></li>
      <li><a href="../logout.php" class="logout-btn">Logout</a></li>
    </ul>
  </nav>
</header>

<main>

  <!-- Chat Header -->
  <div class="chat-header">
    <div class="chat-avatar"><?php echo htmlspecialchars(substr($other['name'], 0, 1)); ?></div>
    <div class="chat-user-info">
      <div class="chat-name"><?php echo htmlspecialchars($other['name']); ?></div>
      <div class="chat-email"><?php echo htmlspecialchars($other['email']); ?></div>
    </div>
    <a href="add_friend.php" class="back-btn">← Back</a>
  </div>

  <!-- Messages -->
  <div id="chat-box">
    <?php if (empty($messages)): ?>
      <p class="no-msgs">No messages yet. Say hello! 👋</p>
    <?php else: ?>
      <?php foreach ($messages as $m): ?>
        <?php $mine = ((int)$m['sender_id'] === $current_id); ?>
        <div class="bubble-wrap <?php echo $mine ? 'mine' : 'theirs'; ?>" data-id="<?php echo (int)$m['id']; ?>">
          <span class="bubble-sender"><?php echo htmlspecialchars($m['sender_name']); ?></span>
          <div class="bubble"><?php echo nl2br(htmlspecialchars($m['text'])); ?></div>
          <span class="bubble-time" data-ts="<?php echo strtotime($m['date']); ?>"></span>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>

  <!-- Input Bar -->
  <div class="chat-input-bar">
    <input type="text" id="msg-input" placeholder="Type a message… (Enter to send)" autocomplete="off" maxlength="1000"/>
    <button id="send-btn" title="Send">➤</button>
  </div>

</main>

<footer>
  <ul>
    <li>hassanqadri047@gmail.com</li>
    <li>Nankana Sahib</li>
  </ul>
</footer>

<script>
  const chatBox   = document.getElementById('chat-box');
  const input     = document.getElementById('msg-input');
  const sendBtn   = document.getElementById('send-btn');
  const currentId = <?php echo $current_id; ?>;
  const otherId   = <?php echo $other_id; ?>;

  function scrollBottom() {
    chatBox.scrollTop = chatBox.scrollHeight;
  }
  scrollBottom();

  // Format from Unix timestamp (seconds) — uses browser's local timezone
  function fmtTime(val) {
    // val may be a Unix timestamp (number) or a DB date string
    const d = (typeof val === 'number' || /^\d+$/.test(String(val)))
      ? new Date(parseInt(val) * 1000)
      : new Date(val.replace(' ', 'T'));
    return d.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
  }

  // Fill times for server-rendered bubbles on page load
  document.querySelectorAll('.bubble-time[data-ts]').forEach(el => {
    el.textContent = fmtTime(parseInt(el.dataset.ts));
  });

  function lastRenderedId() {
    const bubbles = chatBox.querySelectorAll('[data-id]');
    if (!bubbles.length) return 0;
    return parseInt(bubbles[bubbles.length - 1].dataset.id);
  }

  function renderMessages(msgs, appendOnly) {
    const startFrom = appendOnly ? lastRenderedId() : -1;
    let added = 0;

    msgs.forEach(m => {
      if (appendOnly && parseInt(m.id) <= startFrom) return;

      const placeholder = chatBox.querySelector('.no-msgs');
      if (placeholder) placeholder.remove();

      const mine = parseInt(m.sender_id) === currentId;

      const wrap = document.createElement('div');
      wrap.className = 'bubble-wrap ' + (mine ? 'mine' : 'theirs');
      wrap.dataset.id = m.id;

      const senderLabel = document.createElement('span');
      senderLabel.className = 'bubble-sender';
      senderLabel.textContent = m.sender_name;

      const bubble = document.createElement('div');
      bubble.className = 'bubble';
      bubble.textContent = m.text;

      const time = document.createElement('span');
      time.className = 'bubble-time';
      time.textContent = fmtTime(m.unix_ts || m.date);

      wrap.appendChild(senderLabel);
      wrap.appendChild(bubble);
      wrap.appendChild(time);
      chatBox.appendChild(wrap);
      added++;
    });

    if (added > 0) scrollBottom();
  }

  async function sendMsg() {
    const text = input.value.trim();
    if (!text) return;

    sendBtn.disabled = true;
    input.value = '';

    try {
      const fd = new FormData();
      fd.append('text', text);
      const res  = await fetch('chat.php?with=' + otherId, { method: 'POST', body: fd });
      const msgs = await res.json();
      renderMessages(msgs, true);
    } catch (e) {
      console.error('Send error:', e);
    }

    sendBtn.disabled = false;
    input.focus();
  }

  sendBtn.addEventListener('click', sendMsg);
  input.addEventListener('keydown', e => {
    if (e.key === 'Enter' && !e.shiftKey) {
      e.preventDefault();
      sendMsg();
    }
  });

  setInterval(async () => {
    try {
      const res  = await fetch('chat.php?with=' + otherId + '&poll=1');
      const msgs = await res.json();
      renderMessages(msgs, true);
    } catch (e) { /* silent fail */ }
  }, 3000);
</script>

</body>
</html>