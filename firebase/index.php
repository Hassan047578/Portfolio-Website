<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: chat.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChatRoom — Sign In</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
            color: #0a121c;
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .container {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 420px;
            padding: 20px;
        }

        /* ── LOGO ── */
        .logo {
            text-align: center;
            margin-bottom: 40px;
        }
        .logo h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 38px;
            color: purple;
            letter-spacing: -0.02em;
        }
        .logo p {
            font-size: 13px;
            color: #555;
            margin-top: 6px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── CARD ── */
        .card {
            background: rgba(255,255,255,0.6);
            border: 1px solid rgba(128,0,128,0.18);
            border-radius: 16px;
            overflow: hidden;
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 24px rgba(128,0,128,0.1);
        }

        /* ── TABS ── */
        .tabs {
            display: flex;
            border-bottom: 1px solid rgba(128,0,128,0.15);
            background: rgba(255,255,255,0.3);
        }
        .tab {
            flex: 1;
            padding: 16px;
            background: none;
            border: none;
            color: #888;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.02em;
        }
        .tab.active {
            color: purple;
            background: rgba(128,0,128,0.06);
            border-bottom: 2px solid purple;
            margin-bottom: -1px;
            font-weight: 700;
        }
        .tab:hover:not(.active) { color: #2ca8dd; }

        /* ── FORM BODY ── */
        .form-body { padding: 32px; }
        .form-panel { display: none; }
        .form-panel.active { display: block; }

        /* ── FIELDS ── */
        .field { margin-bottom: 20px; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #555;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .field input {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.55);
            border: 1px solid rgba(128,0,128,0.3);
            border-radius: 10px;
            color: #0a121c;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .field input:focus {
            border-color: purple;
            background: rgba(255,255,255,0.9);
        }
        .field input::placeholder { color: #aaa; }

        /* ── BUTTON ── */
        .btn {
            width: 100%;
            padding: 14px;
            background: purple;
            color: white;
            border: 2px solid purple;
            border-radius: 10px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            letter-spacing: 0.02em;
            margin-top: 4px;
        }
        .btn:hover { background: transparent; color: purple; }
        .btn:active { transform: scale(0.98); }

        /* ── MESSAGES ── */
        .msg {
            margin-top: 16px;
            padding: 12px 14px;
            border-radius: 8px;
            font-size: 13px;
            display: none;
        }
        .msg.error {
            background: rgba(232,112,112,0.12);
            color: #c0392b;
            border: 1px solid rgba(232,112,112,0.3);
        }
        .msg.success {
            background: rgba(100,200,120,0.12);
            color: #1a7a3a;
            border: 1px solid rgba(100,200,120,0.3);
        }
        .msg.show { display: block; }

        @media (max-width: 480px) {
            .logo h1 { font-size: 30px; }
            .form-body { padding: 24px; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <h1>ChatRoom</h1>
        <p>Real-time messaging</p>
    </div>

    <div class="card">
        <div class="tabs">
            <button class="tab active" onclick="switchTab('login', event)">Sign In</button>
            <button class="tab" onclick="switchTab('register', event)">Register</button>
        </div>
        <div class="form-body">
            <!-- LOGIN -->
            <div class="form-panel active" id="panel-login">
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="login-email" placeholder="you@example.com">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" id="login-pass" placeholder="••••••••">
                </div>
                <button class="btn" onclick="doLogin()">Sign In</button>
                <div class="msg" id="login-msg"></div>
            </div>

            <!-- REGISTER -->
            <div class="form-panel" id="panel-register">
                <div class="field">
                    <label>Full Name</label>
                    <input type="text" id="reg-name" placeholder="Your name">
                </div>
                <div class="field">
                    <label>Email</label>
                    <input type="email" id="reg-email" placeholder="you@example.com">
                </div>
                <div class="field">
                    <label>Password</label>
                    <input type="password" id="reg-pass" placeholder="Min 6 characters">
                </div>
                <button class="btn" onclick="doRegister()">Create Account</button>
                <div class="msg" id="reg-msg"></div>
            </div>
        </div>
    </div>
</div>

<!-- Firebase SDKs -->
<script type="module">
    import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
    import { getAuth, signInWithEmailAndPassword, createUserWithEmailAndPassword, updateProfile }
        from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

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

    const app  = initializeApp(firebaseConfig);
    const auth = getAuth(app);

    window.doLogin = async function () {
        const email = document.getElementById('login-email').value.trim();
        const pass  = document.getElementById('login-pass').value;
        const msgEl = document.getElementById('login-msg');
        msgEl.className = 'msg';

        if (!email || !pass) {
            showMsg(msgEl, 'Please fill all fields.', 'error'); return;
        }
        try {
            const cred = await signInWithEmailAndPassword(auth, email, pass);
            const user = cred.user;
            const res = await fetch('session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    uid:   user.uid,
                    name:  user.displayName || email.split('@')[0],
                    email: user.email,
                    action: 'login'
                })
            });
            const data = await res.json();
            if (data.ok) window.location.href = 'chat.php';
            else showMsg(msgEl, 'Session error.', 'error');
        } catch (e) {
            showMsg(msgEl, friendlyError(e.code), 'error');
        }
    };

    window.doRegister = async function () {
        const name  = document.getElementById('reg-name').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const pass  = document.getElementById('reg-pass').value;
        const msgEl = document.getElementById('reg-msg');
        msgEl.className = 'msg';

        if (!name || !email || !pass) {
            showMsg(msgEl, 'Please fill all fields.', 'error'); return;
        }
        if (pass.length < 6) {
            showMsg(msgEl, 'Password must be at least 6 characters.', 'error'); return;
        }
        try {
            const cred = await createUserWithEmailAndPassword(auth, email, pass);
            await updateProfile(cred.user, { displayName: name });
            showMsg(msgEl, 'Account created! Signing you in...', 'success');
            const res = await fetch('session.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    uid:   cred.user.uid,
                    name:  name,
                    email: email,
                    action: 'login'
                })
            });
            const data = await res.json();
            if (data.ok) setTimeout(() => window.location.href = 'chat.php', 800);
        } catch (e) {
            showMsg(msgEl, friendlyError(e.code), 'error');
        }
    };

    function showMsg(el, text, type) {
        el.textContent = text;
        el.className = `msg ${type} show`;
    }

    function friendlyError(code) {
        const map = {
            'auth/user-not-found': 'No account found with this email.',
            'auth/wrong-password': 'Incorrect password.',
            'auth/email-already-in-use': 'Email already registered.',
            'auth/invalid-email': 'Invalid email address.',
            'auth/weak-password': 'Password is too weak.',
            'auth/invalid-credential': 'Invalid email or password.',
        };
        return map[code] || 'Something went wrong. Try again.';
    }
</script>

<script>
    function switchTab(tab, event) {
        document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.form-panel').forEach(p => p.classList.remove('active'));
        document.querySelector(`#panel-${tab}`).classList.add('active');
        event.target.classList.add('active');
    }

    // Allow Enter key
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const activePanel = document.querySelector('.form-panel.active').id;
            if (activePanel === 'panel-login') window.doLogin && window.doLogin();
            else window.doRegister && window.doRegister();
        }
    });
</script>
</body>
</html>