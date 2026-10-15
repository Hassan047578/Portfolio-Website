<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$current_uid  = $_SESSION['user_id'];
$current_name = $_SESSION['user_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatRoom — Live</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
            color: #0a121c;
            font-family: 'DM Sans', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .chat-wrapper {
            position: relative;
            z-index: 1;
            width: 480px;
            height: 680px;
            background: rgba(255,255,255,0.6);
            border: 1px solid rgba(128,0,128,0.18);
            border-radius: 20px;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 4px 32px rgba(128,0,128,0.15);
            backdrop-filter: blur(10px);
        }

        /* ── Header ── */
        .chat-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(128,0,128,0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255,255,255,0.5);
            flex-shrink: 0;
        }

        .header-left {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-icon {
            width: 38px;
            height: 38px;
            background: purple;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'DM Serif Display', serif;
            font-size: 16px;
            color: white;
            font-weight: bold;
        }

        .header-info h2 {
            font-family: 'DM Serif Display', serif;
            font-size: 17px;
            color: purple;
            letter-spacing: -0.01em;
        }

        .header-info .status {
            font-size: 11px;
            color: #2a9a2a;
            display: flex;
            align-items: center;
            gap: 4px;
            margin-top: 2px;
        }

        .status-dot {
            width: 6px;
            height: 6px;
            background: #2a9a2a;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .header-right {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .user-badge {
            font-size: 12px;
            color: #555;
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(128,0,128,0.2);
            padding: 5px 10px;
            border-radius: 20px;
            max-width: 120px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .logout-btn {
            font-size: 11px;
            color: #e838f8;
            text-decoration: none;
            padding: 5px 12px;
            border: 1px solid #e838f8;
            border-radius: 20px;
            transition: all 0.2s;
            cursor: pointer;
            background: none;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
        }

        .logout-btn:hover { background: #e838f8; color: white; }

        /* ── Messages ── */
        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        #chat-box::-webkit-scrollbar { width: 3px; }
        #chat-box::-webkit-scrollbar-thumb { background: rgba(128,0,128,0.2); border-radius: 3px; }

        .message-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            animation: msgIn 0.2s ease;
        }

        @keyframes msgIn {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .message-row.own { flex-direction: row-reverse; }

        .avatar {
            width: 30px;
            height: 30px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            flex-shrink: 0;
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(128,0,128,0.2);
            color: #555;
        }

        .message-row.own .avatar {
            background: purple;
            border-color: purple;
            color: white;
        }

        .bubble-wrap {
            display: flex;
            flex-direction: column;
            max-width: 74%;
        }

        .message-row.own .bubble-wrap { align-items: flex-end; }

        .sender-name {
            font-size: 11px;
            color: #888;
            margin-bottom: 4px;
            padding: 0 4px;
            letter-spacing: 0.02em;
        }

        .bubble {
            padding: 10px 14px;
            border-radius: 14px;
            font-size: 13.5px;
            line-height: 1.6;
            word-break: break-word;
        }

        .message-row:not(.own) .bubble {
            background: rgba(255,255,255,0.75);
            color: #0a121c;
            border: 1px solid rgba(128,0,128,0.15);
            border-bottom-left-radius: 4px;
        }

        .message-row.own .bubble {
            background: purple;
            color: white;
            border: 1px solid purple;
            border-bottom-right-radius: 4px;
        }

        .msg-time {
            font-size: 10px;
            color: #999;
            margin-top: 4px;
            padding: 0 4px;
        }

        /* ── Files ── */
        .chat-img {
            display: block;
            max-width: 100%;
            max-height: 220px;
            border-radius: 10px;
            object-fit: cover;
            cursor: pointer;
            border: 1px solid rgba(128,0,128,0.2);
        }

        .chat-video {
            display: block;
            max-width: 100%;
            max-height: 200px;
            border-radius: 10px;
        }

        .file-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 13px;
            background: rgba(255,255,255,0.7);
            border: 1px solid rgba(128,0,128,0.2);
            border-radius: 10px;
            color: #0a121c;
            text-decoration: none;
            font-size: 12px;
            word-break: break-all;
            transition: border-color 0.2s;
        }

        .file-link:hover { border-color: purple; }

        /* ── File Preview Bar ── */
        #file-preview-bar {
            display: none;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            background: rgba(255,255,255,0.5);
            border-top: 1px solid rgba(128,0,128,0.12);
            font-size: 12px;
            color: #777;
            flex-shrink: 0;
        }

        #file-preview-bar.active { display: flex; }

        #preview-thumb {
            display: none;
            width: 32px;
            height: 32px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid rgba(128,0,128,0.2);
        }

        #preview-icon { display: none; font-size: 22px; }
        #preview-name { flex: 1; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }

        #remove-file {
            cursor: pointer;
            color: #aaa;
            font-size: 14px;
            padding: 3px 7px;
            border-radius: 50%;
            transition: background 0.15s;
        }

        #remove-file:hover { background: rgba(128,0,128,0.1); color: purple; }

        /* ── Emoji Picker ── */
        #emoji-picker {
            position: absolute;
            bottom: 72px;
            left: 14px;
            width: 452px;
            background: rgba(255,255,255,0.92);
            border: 1px solid rgba(128,0,128,0.2);
            border-radius: 14px;
            padding: 10px;
            box-shadow: 0 8px 32px rgba(128,0,128,0.15);
            z-index: 999;
            backdrop-filter: blur(8px);
        }

        #emoji-list {
            display: flex;
            flex-wrap: wrap;
            gap: 3px;
            max-height: 190px;
            overflow-y: auto;
        }

        #emoji-list span {
            font-size: 21px;
            cursor: pointer;
            padding: 5px;
            border-radius: 8px;
            transition: background 0.15s;
            line-height: 1;
        }

        #emoji-list span:hover { background: rgba(128,0,128,0.1); }

        /* ── Input ── */
        .chat-input {
            padding: 12px 14px;
            border-top: 1px solid rgba(128,0,128,0.12);
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.5);
            flex-shrink: 0;
        }

        #chat_file { display: none; }

        .icon-btn {
            width: 36px;
            height: 36px;
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
            flex-shrink: 0;
            color: #888;
            text-decoration: none;
        }

        .icon-btn:hover { background: rgba(128,0,128,0.08); color: purple; }
        .icon-btn.has-file { color: purple; }

        #message {
            flex: 1;
            padding: 10px 16px;
            background: rgba(255,255,255,0.6);
            border: 1px solid rgba(128,0,128,0.3);
            border-radius: 24px;
            color: #0a121c;
            font-family: 'DM Sans', sans-serif;
            font-size: 13px;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }

        #message:focus { border-color: purple; background: rgba(255,255,255,0.9); }
        #message::placeholder { color: #aaa; }

        #sendBtn {
            width: 38px;
            height: 38px;
            background: purple;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s, transform 0.1s;
            flex-shrink: 0;
        }

        #sendBtn:hover { background: #5a005a; }
        #sendBtn:active { transform: scale(0.92); }

        /* ── Scroll btn ── */
        #scroll-down-btn {
            display: none;
            position: absolute;
            bottom: 76px;
            right: 16px;
            width: 32px;
            height: 32px;
            background: purple;
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 14px;
            cursor: pointer;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(128,0,128,0.3);
            z-index: 100;
        }

        #scroll-down-btn.visible { display: flex; }

        /* ── Upload progress ── */
        #upload-progress {
            display: none;
            position: absolute;
            bottom: 72px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(255,255,255,0.9);
            border: 1px solid rgba(128,0,128,0.2);
            border-radius: 20px;
            padding: 6px 14px;
            font-size: 12px;
            color: purple;
            z-index: 200;
            white-space: nowrap;
            backdrop-filter: blur(6px);
        }

        #upload-progress.show { display: block; }

        /* ── Empty state ── */
        .empty-state {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #aaa;
            font-size: 13px;
            gap: 8px;
        }

        .empty-state .icon { font-size: 32px; }
    </style>
</head>
<body>

<div class="chat-wrapper">

    <div class="chat-header">
        <div class="header-left">
            <div class="header-icon">C</div>
            <div class="header-info">
                <h2>Group Chat</h2>
                <div class="status"><span class="status-dot"></span> Live</div>
            </div>
        </div>
        <div class="header-right">
            <span class="user-badge"><?= htmlspecialchars($current_name) ?></span>
            <button class="logout-btn" onclick="doLogout()">Logout</button>
        </div>
    </div>

    <div id="chat-box">
        <div class="empty-state" id="empty-state">
            <span class="icon">💬</span>
            <span>No messages yet. Say hello!</span>
        </div>
    </div>

    <button id="scroll-down-btn" title="Latest messages">↓</button>
    <div id="upload-progress">Uploading…</div>

    <!-- File preview bar -->
    <div id="file-preview-bar">
        <img id="preview-thumb" src="" alt="">
        <span id="preview-icon"></span>
        <span id="preview-name"></span>
        <span id="remove-file" title="Remove">✕</span>
    </div>

    <!-- Emoji Picker -->
    <div id="emoji-picker" style="display:none;">
        <div id="emoji-list"></div>
    </div>

    <div class="chat-input">
        <input type="file" id="chat_file"
               accept="image/*,video/mp4,video/webm,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,text/plain">
        <label for="chat_file" class="icon-btn" id="attachBtn" title="Attach file">📎</label>
        <button class="icon-btn" id="emojiBtn">😊</button>
        <input type="text" id="message" placeholder="Type a message…">
        <button id="sendBtn">&#9658;</button>
    </div>

</div>

<!-- Firebase -->
<script type="module">
    import { initializeApp }
        from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
    import { getDatabase, ref, push, onChildAdded, serverTimestamp, limitToLast, query }
        from "https://www.gstatic.com/firebasejs/10.12.0/firebase-database.js";

    const firebaseConfig = {
        apiKey: "AIzaSyB0Ng5S-Uez4feOoiE2o-r9zs8A7P19I2o",
        authDomain: "chatroom-10023.firebaseapp.com",
        databaseURL: "https://chatroom-10023-default-rtdb.firebaseio.com",
        projectId: "chatroom-10023",
        storageBucket: "chatroom-10023.firebasestorage.app",
        messagingSenderId: "605746052757",
        appId: "1:605746052757:web:d2d6359ab9796f5886259c",
        measurementId: "G-LCPS701VLC"
    };

    const app = initializeApp(firebaseConfig);
    const db  = getDatabase(app);

    const CURRENT_UID  = <?= json_encode($current_uid) ?>;
    const CURRENT_NAME = <?= json_encode($current_name) ?>;

    const chatBox    = document.getElementById('chat-box');
    const emptyState = document.getElementById('empty-state');
    const scrollBtn  = document.getElementById('scroll-down-btn');
    const uploadProg = document.getElementById('upload-progress');
    let messageCount = 0;

    // ── Scroll helpers ──
    function isNearBottom() {
        return chatBox.scrollHeight - chatBox.scrollTop - chatBox.clientHeight < 100;
    }
    function scrollBottom() {
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    chatBox.addEventListener('scroll', () => {
        scrollBtn.classList.toggle('visible', !isNearBottom());
    });

    document.getElementById('scroll-down-btn').addEventListener('click', () => {
        scrollBottom();
        scrollBtn.classList.remove('visible');
    });

    // ── Render a message ──
    function renderMessage(data) {
        if (emptyState) emptyState.remove();
        messageCount++;

        const isOwn  = data.uid === CURRENT_UID;
        const name   = data.name || 'Unknown';
        const words  = name.split(' ');
        let initials = words[0][0].toUpperCase();
        if (words[1]) initials += words[1][0].toUpperCase();

        const ts   = data.timestamp ? new Date(data.timestamp) : new Date();
        const time = ts.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

        let fileHtml = '';
        if (data.file_url) {
            const ft    = data.file_type || '';
            const fname = data.file_name || 'file';
            const url   = data.file_url;

            if (ft.startsWith('image/')) {
                fileHtml = `
                <div style="max-width:100%;">
                    <a href="${url}" target="_blank">
                        <img src="${url}" alt="Image" class="chat-img">
                    </a>
                </div>`;
            } else if (ft.startsWith('video/')) {
                fileHtml = `
                <div style="max-width:100%;">
                    <video controls class="chat-video">
                        <source src="${url}" type="${ft}">
                    </video>
                </div>`;
            } else {
                const icons = {
                    'application/pdf': '📄',
                    'text/plain': '📃',
                    'video/mp4': '🎬',
                    'video/webm': '🎬',
                };
                const icon = Object.entries(icons).find(([k]) => ft.includes(k.split('/')[1]))?.[1] || '📎';
                fileHtml = `<a href="${url}" download="${fname}" class="file-link">${icon} ${fname}</a>`;
            }
        }

        const textHtml = data.message
            ? `<div class="bubble">${escapeHtml(data.message)}</div>` : '';

        const row = document.createElement('div');
        row.className = `message-row ${isOwn ? 'own' : ''}`;
        row.innerHTML = `
            <div class="avatar">${initials}</div>
            <div class="bubble-wrap">
                <div class="sender-name">${escapeHtml(name)}</div>
                ${fileHtml}
                ${textHtml}
                <div class="msg-time">${time}</div>
            </div>`;

        const wasNearBottom = isNearBottom();
        chatBox.appendChild(row);
        if (wasNearBottom || messageCount <= 1) scrollBottom();
    }

    // ── Listen for new messages (last 100) ──
    const messagesRef = query(ref(db, 'messages'), limitToLast(100));
    onChildAdded(messagesRef, (snapshot) => {
        renderMessage(snapshot.val());
    });

    // ── Send message ──
    window.sendMessage = async function () {
        const msg  = document.getElementById('message').value.trim();
        const file = document.getElementById('chat_file').files[0];

        if (!msg && !file) return;

        let file_url  = null;
        let file_type = null;
        let file_name = null;

        if (file) {
            uploadProg.classList.add('show');
            const fd = new FormData();
            fd.append('chat_file', file);
            try {
                const res  = await fetch('upload.php', { method: 'POST', body: fd });
                const data = await res.json();
                if (data.ok) {
                    file_url  = data.url;
                    file_type = data.file_type;
                    file_name = data.filename;
                } else {
                    alert('Upload failed: ' + (data.error || 'Unknown error'));
                    uploadProg.classList.remove('show');
                    return;
                }
            } catch (e) {
                alert('Upload failed. Please try again.');
                uploadProg.classList.remove('show');
                return;
            }
            uploadProg.classList.remove('show');
        }

        await push(ref(db, 'messages'), {
            uid:       CURRENT_UID,
            name:      CURRENT_NAME,
            message:   msg,
            file_url:  file_url,
            file_type: file_type,
            file_name: file_name,
            timestamp: Date.now(),
        });

        document.getElementById('message').value = '';
        document.getElementById('emoji-picker').style.display = 'none';
        clearFile();
        scrollBottom();
    };

    // ── Logout ──
    window.doLogout = async function () {
        await fetch('session.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: 'logout' })
        });
        window.location.href = 'index.php';
    };

    function escapeHtml(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }
</script>

<script>
    /* ── Emoji Setup ── */
    const emojis = [
        '😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊',
        '😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗',
        '🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥',
        '😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜',
        '😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','☹️',
        '🙁','😖','😞','😟','😤','😢','😭','😦','😧','😨',
        '😩','🤯','😬','😰','😱','🥵','🥶','😳','🤪','😵',
        '😠','😡','🤬','😷','🤒','🤕','🤢','🤮','🤧','🥳',
        '👍','👎','👏','🙌','🤝','🙏','❤️','🧡','💛','💚',
        '💙','💜','🔥','⭐','🌟','💫','✨','🎉','🎊','🚀'
    ];

    const emojiList = document.getElementById('emoji-list');
    emojis.forEach(emoji => {
        const span = document.createElement('span');
        span.textContent = emoji;
        span.onclick = () => {
            const inp = document.getElementById('message');
            inp.value += emoji;
            inp.focus();
        };
        emojiList.appendChild(span);
    });

    document.getElementById('emojiBtn').addEventListener('click', e => {
        e.stopPropagation();
        const picker = document.getElementById('emoji-picker');
        picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
    });

    document.addEventListener('click', () => {
        document.getElementById('emoji-picker').style.display = 'none';
    });

    document.getElementById('emoji-picker').addEventListener('click', e => e.stopPropagation());

    /* ── File Attach ── */
    const fileInput    = document.getElementById('chat_file');
    const attachLabel  = document.getElementById('attachBtn');
    const previewBar   = document.getElementById('file-preview-bar');
    const previewThumb = document.getElementById('preview-thumb');
    const previewIcon  = document.getElementById('preview-icon');
    const previewName  = document.getElementById('preview-name');

    fileInput.addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        previewName.textContent = file.name;
        previewThumb.style.display = 'none';
        previewIcon.style.display  = 'none';

        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => {
                previewThumb.src = e.target.result;
                previewThumb.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            const icons = {
                'application/pdf': '📄', 'text/plain': '📃',
                'video/mp4': '🎬', 'video/webm': '🎬',
            };
            previewIcon.textContent = icons[file.type] || '📎';
            previewIcon.style.display = 'block';
        }

        previewBar.classList.add('active');
        attachLabel.classList.add('has-file');
    });

    document.getElementById('remove-file').addEventListener('click', clearFile);

    function clearFile() {
        fileInput.value = '';
        previewBar.classList.remove('active');
        previewThumb.style.display = 'none';
        previewIcon.style.display  = 'none';
        previewThumb.src = '';
        previewName.textContent = '';
        attachLabel.classList.remove('has-file');
    }

    window.clearFile = clearFile;

    /* ── Send ── */
    document.getElementById('sendBtn').addEventListener('click', () => window.sendMessage());
    document.getElementById('message').addEventListener('keypress', e => {
        if (e.key === 'Enter') window.sendMessage();
    });
</script>

</body>
</html>