<?php
session_start();
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hassan Wazir | Live Chat</title>
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
            color: #e2e8f0;
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

        .page-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 0 30px;
        }

        .chat-wrapper {
            width: 500px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(128, 0, 128, 0.15);
            display: flex;
            flex-direction: column;
            height: 580px;
            overflow: hidden;
        }

        .chat-header {
            padding: 16px 20px;
            border-bottom: 1px solid rgba(128, 0, 128, 0.15);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 20px 20px 0 0;
        }

        .chat-header-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chat-header-icon {
            width: 38px;
            height: 38px;
            background: purple;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 16px;
        }

        .chat-header h2 {
            font-size: 17px;
            font-weight: 700;
            color: black;
        }

        .chat-header p {
            font-size: 11px;
            color: #22c55e;
        }

        .logout-btn {
            font-size: 12px;
            color: purple;
            text-decoration: none;
            font-weight: 600;
            padding: 6px 14px;
            border: 2px solid purple;
            border-radius: 20px;
            transition: 0.3s;
        }

        .logout-btn:hover {
            background: purple;
            color: white;
        }

        #chat-box {
            flex: 1;
            overflow-y: auto;
            padding: 20px 18px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            background: rgba(255, 255, 255, 0.3);
        }

        #chat-box::-webkit-scrollbar { width: 5px; }
        #chat-box::-webkit-scrollbar-thumb {
            background: purple;
            border-radius: 10px;
        }

        .message-row {
            display: flex;
            align-items: flex-end;
            gap: 8px;
        }

        .message-row.own {
            flex-direction: row-reverse;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(238, 174, 202, 0.7);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: bold;
            color: purple;
            flex-shrink: 0;
            border: 1px solid rgba(128, 0, 128, 0.2);
        }

        .message-row.own .avatar {
            background: purple;
            color: #fff;
            border: none;
        }

        .bubble-wrap {
            display: flex;
            flex-direction: column;
            max-width: 72%;
        }

        .message-row.own .bubble-wrap {
            align-items: flex-end;
        }

        .sender-name {
            font-size: 11px;
            color: #888;
            margin-bottom: 3px;
            padding-left: 4px;
        }

        .message-row.own .sender-name {
            padding-left: 0;
            padding-right: 4px;
        }

        .bubble {
            padding: 10px 14px;
            border-radius: 18px;
            font-size: 13.5px;
            line-height: 1.55;
            word-break: break-word;
        }

        .message-row:not(.own) .bubble {
            background: rgba(255, 255, 255, 0.85);
            color: #222;
            border: 1px solid rgba(128, 0, 128, 0.15);
            border-bottom-left-radius: 4px;
        }

        .message-row.own .bubble {
            background: purple;
            color: #fff;
            border-bottom-right-radius: 4px;
        }

        .bubble.image-bubble {
            padding: 6px;
            background: transparent;
            border: none;
        }

        .message-row:not(.own) .bubble.image-bubble {
            background: rgba(255,255,255,0.85);
            border: 1px solid rgba(128, 0, 128, 0.15);
        }

        .message-row.own .bubble.image-bubble {
            background: rgba(128, 0, 128, 0.12);
        }

        .img-blur-wrap {
            position: relative;
            display: inline-block;
            border-radius: 12px;
            overflow: hidden;
        }

        .chat-img {
            max-width: 220px;
            max-height: 180px;
            border-radius: 12px;
            display: block;
            object-fit: cover;
            border: 2px solid rgba(128, 0, 128, 0.2);
        }

        .chat-img.blurred {
            filter: blur(12px);
            transform: scale(1.05);
        }

        .chat-img.revealed {
            cursor: pointer;
            filter: none;
        }

        .dl-overlay-btn {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 48px;
            height: 48px;
            background: rgba(128, 0, 128, 0.85);
            color: white;
            border: none;
            border-radius: 50%;
            font-size: 24px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 12px rgba(0,0,0,0.3);
            transition: background 0.2s, transform 0.2s;
            line-height: 1;
        }

        .dl-overlay-btn:hover {
            background: purple;
            transform: translate(-50%, -50%) scale(1.12);
        }

        .dl-overlay-btn:active {
            transform: translate(-50%, -50%) scale(0.95);
        }

        .dl-overlay-btn.loading {
            pointer-events: none;
            opacity: 0.7;
            font-size: 14px;
        }

        .msg-time {
            font-size: 10px;
            color: #aaa;
            margin-top: 4px;
            padding: 0 4px;
        }

        .chat-input {
            padding: 14px 16px;
            border-top: 1px solid rgba(128, 0, 128, 0.15);
            display: flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.6);
            border-radius: 0 0 20px 20px;
            flex-wrap: wrap;
        }

        #message {
            flex: 1;
            padding: 10px 16px;
            border: 1px solid rgba(128, 0, 128, 0.3);
            border-radius: 24px;
            font-size: 13px;
            color: #222;
            outline: none;
            background: rgba(255, 255, 255, 0.8);
            transition: border-color 0.3s;
            min-width: 0;
        }

        #message:focus {
            border-color: purple;
        }

        #message::placeholder {
            color: #999;
        }

        .img-upload-label {
            width: 42px;
            height: 42px;
            background: rgba(255, 255, 255, 0.8);
            border: 2px solid purple;
            border-radius: 50%;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            flex-shrink: 0;
        }

        .img-upload-label:hover {
            background: purple;
            color: white;
        }

        #imageFile {
            display: none;
        }

        #sendBtn {
            width: 42px;
            height: 42px;
            background: purple;
            color: #fff;
            border: 2px solid purple;
            border-radius: 50%;
            font-size: 16px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: 0.3s;
            flex-shrink: 0;
        }

        #sendBtn:hover {
            background: transparent;
            color: purple;
        }

        #sendBtn:active {
            transform: scale(0.93);
        }

        #preview-bar {
            width: 100%;
            display: none;
            align-items: center;
            gap: 10px;
            padding: 6px 4px 0;
        }

        #preview-bar img {
            height: 48px;
            width: 48px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid purple;
        }

        #preview-bar span {
            font-size: 12px;
            color: #555;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        #cancelPreview {
            font-size: 18px;
            color: red;
            cursor: pointer;
            background: none;
            border: none;
            line-height: 1;
        }

        /* Lightbox */
        #lightbox {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.82);
            z-index: 9999;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            gap: 14px;
        }

        #lightbox.active {
            display: flex;
        }

        #lightbox img {
            max-width: 90vw;
            max-height: 80vh;
            border-radius: 14px;
            box-shadow: 0 4px 40px rgba(128,0,128,0.4);
        }

        #lightbox-close {
            position: absolute;
            top: 20px;
            right: 26px;
            font-size: 32px;
            color: white;
            cursor: pointer;
            line-height: 1;
        }

        #lightbox-download {
            padding: 10px 28px;
            background: purple;
            color: white;
            border-radius: 24px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: 0.3s;
            cursor: pointer;
            border: none;
        }

        #lightbox-download:hover {
            background: white;
            color: purple;
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

        /* ── File Upload Modal ── */
        #fileModal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 8888;
            justify-content: center;
            align-items: center;
        }

        #fileModal.active {
            display: flex;
        }

        .modal-box {
            background: white;
            border-radius: 16px;
            padding: 30px 36px;
            width: 320px;
            box-shadow: 0 8px 40px rgba(128, 0, 128, 0.25);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .modal-box h3 {
            font-size: 18px;
            font-weight: 700;
            color: #222;
        }

        .modal-file-input-label {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1.5px solid #ccc;
            border-radius: 8px;
            padding: 8px 14px;
            cursor: pointer;
            font-size: 13px;
            color: #444;
            width: 100%;
            background: #f9f9f9;
        }

        .modal-file-input-label span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .modal-preview-img {
            max-width: 100%;
            max-height: 160px;
            border-radius: 10px;
            border: 2px solid purple;
            display: none;
            object-fit: cover;
        }

        .modal-buttons {
            display: flex;
            gap: 12px;
            width: 100%;
            justify-content: center;
        }

        .modal-send-btn {
            padding: 9px 28px;
            background: purple;
            color: white;
            border: 2px solid purple;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .modal-send-btn:hover {
            background: white;
            color: purple;
        }

        .modal-cancel-btn {
            padding: 9px 28px;
            background: white;
            color: #555;
            border: 2px solid #ccc;
            border-radius: 24px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .modal-cancel-btn:hover {
            border-color: red;
            color: red;
        }
    </style>
</head>
<body>

    <section class="page-section">
        <div class="chat-wrapper">

            <div class="chat-header">
                <div class="chat-header-left">
                    <div class="chat-header-icon">💬</div>
                    <div>
                        <h2>Live Chat</h2>
                        <p>● Online</p>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>

            <div id="chat-box"></div>

            <div class="chat-input">
                <div id="preview-bar">
                    <img id="preview-thumb" src="" alt="preview">
                    <span id="preview-name"></span>
                    <button id="cancelPreview" title="Remove">✕</button>
                </div>

                <!-- 🖼️ button now opens modal instead of file picker directly -->
                <div class="img-upload-label" id="openModalBtn" title="Send Image">🖼️</div>
                <input type="file" id="imageFile" accept="image/*">
                <input type="text" id="message" placeholder="Type a message...">
                <button id="sendBtn">&#9658;</button>
            </div>

        </div>
    </section>

    
    <div id="lightbox">
        <span id="lightbox-close">✕</span>
        <img id="lightbox-img" src="" alt="Full Image">
        <button id="lightbox-download">⬇ Download</button>
    </div>

    
    <div id="fileModal">
        <div class="modal-box">
            <h3>Send File</h3>

            
            <label class="modal-file-input-label" for="modalFileInput">
                <span id="modalFileName">No file chosen</span>
            </label>
            <input type="file" id="modalFileInput" accept="image/*" style="display:none;">

            
            <img id="modalPreviewImg" class="modal-preview-img" src="" alt="Preview">

            <div class="modal-buttons">
                <button class="modal-send-btn" id="modalSendBtn">Send</button>
                <button class="modal-cancel-btn" id="modalCancelBtn">Cancel</button>
            </div>
        </div>
    </div>

<script>
    
    var downloadedImages = new Set();

    function scrollBottom() {
        var box = document.getElementById('chat-box');
        box.scrollTop = box.scrollHeight;
    }

    
    function reRevealDownloaded() {
        downloadedImages.forEach(function(uid) {
            var blurWrap = document.getElementById('blur_' + uid);
            var revealed = document.getElementById('revealed_' + uid);
            if (blurWrap) blurWrap.style.display = 'none';
            if (revealed) revealed.style.display = 'block';
        });
    }

    
    function downloadImage(src, fileName, uid) {
        var btn = document.querySelector('#blur_' + uid + ' .dl-overlay-btn');
        if (btn) {
            btn.classList.add('loading');
            btn.innerHTML = '⏳';
        }

        fetch(src)
            .then(function(response) { return response.blob(); })
            .then(function(blob) {
                var blobUrl = URL.createObjectURL(blob);
                var a = document.createElement('a');
                a.href = blobUrl;
                a.download = fileName;
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
                setTimeout(function() { URL.revokeObjectURL(blobUrl); }, 1000);

                downloadedImages.add(uid);
                var blurWrap = document.getElementById('blur_' + uid);
                var revealed = document.getElementById('revealed_' + uid);
                if (blurWrap) blurWrap.style.display = 'none';
                if (revealed) revealed.style.display = 'block';
            })
            .catch(function() {
                window.open(src, '_blank');
                downloadedImages.add(uid);
                var blurWrap = document.getElementById('blur_' + uid);
                var revealed = document.getElementById('revealed_' + uid);
                if (blurWrap) blurWrap.style.display = 'none';
                if (revealed) revealed.style.display = 'block';
            });
    }

    
    function openLightbox(src, fileName) {
        document.getElementById('lightbox-img').src = src;
        document.getElementById('lightbox').classList.add('active');

        document.getElementById('lightbox-download').onclick = function() {
            fetch(src)
                .then(function(r) { return r.blob(); })
                .then(function(blob) {
                    var blobUrl = URL.createObjectURL(blob);
                    var a = document.createElement('a');
                    a.href = blobUrl;
                    a.download = fileName;
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    setTimeout(function() { URL.revokeObjectURL(blobUrl); }, 1000);
                })
                .catch(function() { window.open(src, '_blank'); });
        };
    }

    
    $('#openModalBtn').click(function() {
        
        $('#modalFileInput').val('');
        $('#modalFileName').text('No file chosen');
        $('#modalPreviewImg').hide().attr('src', '');
        $('#fileModal').addClass('active');
    });

    
    $('#modalFileInput').change(function() {
        var file = this.files[0];
        if (!file) return;
        $('#modalFileName').text(file.name);
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#modalPreviewImg').attr('src', e.target.result).show();
        };
        reader.readAsDataURL(file);
    });

    
    $('#modalCancelBtn').click(function() {
        $('#fileModal').removeClass('active');
        $('#modalFileInput').val('');
        $('#modalFileName').text('No file chosen');
        $('#modalPreviewImg').hide().attr('src', '');
    });

    
    $('#modalSendBtn').click(function() {
        var file = $('#modalFileInput')[0].files[0];
        if (!file) {
            alert('Please choose a file first.');
            return;
        }

        
        $('#fileModal').removeClass('active');

        
        var dt = new DataTransfer();
        dt.items.add(file);
        $('#imageFile')[0].files = dt.files;

        
        var reader = new FileReader();
        reader.onload = function(e) {
            $('#preview-thumb').attr('src', e.target.result);
            $('#preview-name').text(file.name);
            $('#preview-bar').css('display', 'flex');
            $('#message').attr('placeholder', 'Add a caption (optional)...');
        };
        reader.readAsDataURL(file);

        
        sendMessage();
    });

    
    $('#cancelPreview').click(function() {
        $('#imageFile').val('');
        $('#preview-bar').hide();
        $('#preview-thumb').attr('src', '');
        $('#preview-name').text('');
        $('#message').attr('placeholder', 'Type a message...');
    });

    
    function sendMessage() {
        var msg = $('#message').val().trim();
        var file = $('#imageFile')[0].files[0];
        if (msg === '' && !file) return;

        var formData = new FormData();
        formData.append('message', msg);
        if (file) formData.append('image', file);

        $.ajax({
            url: 'send.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() {
                $('#message').val('').attr('placeholder', 'Type a message...');
                $('#imageFile').val('');
                $('#preview-bar').hide();
                $('#preview-thumb').attr('src', '');
                $('#preview-name').text('');
                $('#chat-box').load('fetch.php', function() {
                    scrollBottom();
                    reRevealDownloaded();
                });
            }
        });
    }

    $('#sendBtn').click(sendMessage);
    $('#message').keypress(function(e) {
        if (e.which === 13) sendMessage();
    });

    
    setInterval(function() {
        $('#chat-box').load('fetch.php', function() {
            scrollBottom();
            reRevealDownloaded();
        });
    }, 2000);

    
    $('#chat-box').load('fetch.php', scrollBottom);

    
    $('#lightbox-close').click(function() {
        $('#lightbox').removeClass('active');
    });
    $('#lightbox').click(function(e) {
        if (e.target === this) $(this).removeClass('active');
    });

    
    $('#fileModal').click(function(e) {
        if (e.target === this) {
            $(this).removeClass('active');
        }
    });
</script>

</body>
</html>
