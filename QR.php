<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hassan Wazir | QR Code App</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://unpkg.com/html5-qrcode"></script>

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
        padding: 40px 0 30px;
    }

    .page-section h1 {
        text-align: center;
        font-size: 30px;
        color: black;
        margin-bottom: 30px;
        font-weight: 700;
    }

    .page-section h1 span {
        color: purple;
    }

    .qr-grid {
        display: flex;
        gap: 24px;
        justify-content: center;
        flex-wrap: wrap;
        width: 90%;
        max-width: 900px;
        margin: auto;
    }

    .card {
        flex: 1;
        min-width: 280px;
        max-width: 420px;
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(10px);
        padding: 25px;
        border-radius: 20px;
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 8px 32px rgba(128, 0, 128, 0.15);
        color: black;
        text-align: center;
    }

    .card h3 {
        font-size: 18px;
        font-weight: 700;
        color: purple;
        margin-bottom: 16px;
    }

    input[type="text"] {
        width: 100%;
        padding: 10px 14px;
        border-radius: 10px;
        border: 1px solid rgba(128, 0, 128, 0.3);
        background: rgba(255, 255, 255, 0.8);
        color: black;
        font-size: 14px;
        outline: none;
        transition: border 0.3s;
        margin-bottom: 12px;
    }

    input[type="text"]:focus {
        border-color: purple;
    }

    input[type="text"]::placeholder {
        color: #888;
    }

    input[type="file"] {
        margin: 10px 0;
        font-size: 13px;
        color: #444;
        width: 100%;
    }

    .btn-row {
        display: flex;
        gap: 8px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 10px;
    }

    button {
        padding: 10px 16px;
        border: 2px solid purple;
        background: purple;
        color: white;
        border-radius: 10px;
        cursor: pointer;
        font-weight: bold;
        font-size: 13px;
        transition: 0.3s;
    }

    button:hover {
        background: transparent;
        color: purple;
    }

    #qrcode {
        margin-top: 15px;
        display: flex;
        justify-content: center;
    }

    #qrcode img,
    #qrcode canvas {
        border-radius: 10px;
        border: 2px solid rgba(128, 0, 128, 0.2);
        padding: 8px;
        background: white;
    }

    #reader {
        width: 100%;
        margin-top: 15px;
        border-radius: 10px;
        overflow: hidden;
    }

    .result {
        margin-top: 12px;
        font-weight: bold;
        color: purple;
        font-size: 14px;
        word-break: break-all;
    }

    .result a {
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
        margin-top: 20px;
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
</style>
</head>

<body>

    <header>
        <div class="container">
            <nav>
                <ul id="menu">
                    <li><a href="index.php">Home</a></li>
                    <!--<li><a href="tutorial.php">Tutorials</a></li>-->
                    <!--<li><a href="public_gallery.php">Gallery</a></li>-->
                    <!--<li><a href="#projects">Projects</a></li>-->
                    <!--<li><a href="#contact">Contact</a></li>-->
                    <!--<li><a href="QR.php">QR</a></li>-->
                    <!--<li><a href="chatting/index.php">Chat</a></li>-->
                    <!--<li><a href="location/location.php">Location</a></li>-->
                </ul>
                <div class="auth-buttons">
                    <a href="cv_maker/bio.php" class="makecv-btn">Make CV</a>
                    <a href="login.php" class="login-btn">Login</a>
                    <a href="signup.php" class="signup-btn">Signup</a>
                </div>
            </nav>
        </div>
    </header>

    <section class="page-section">
        <div class="container">
            <h1> QR Code <span>Generator & Scanner</span></h1>
        </div>

        <div class="qr-grid">

            
            <div class="card">
                <h3>⚡Generate QR Code</h3>
                <input type="text" id="qrText" placeholder="Enter URL or text">
                <div class="btn-row">
                    <button onclick="generateQR()">Generate</button>
                <!--    <button onclick="downloadQR()">💾 Save</button>-->
                <!--    <button onclick="shareQR()">🔗 Share</button>-->
                </div>
                <div id="qrcode"></div>
            </div>

            
            <div class="card">
                <h3> Scan QR Code</h3>
                <div class="btn-row">
                    <button onclick="startCameraScan()">Camera Scan</button>
                </div>
                <input type="file" id="fileInput" accept="image/*">
                <div class="btn-row">
                    <button onclick="scanSelectedFile()"> Scan File</button>
                </div>
                <div id="reader"></div>
                <div class="result" id="scanResult"></div>
            </div>

        </div>
    </section>

    <footer>
        <ul>
            <li>hassanqadri047@gmail.com</li>
            <li>Nankana Sahib</li>
        </ul>
    </footer>

<script>
    let qrContainer = document.getElementById("qrcode");
    let html5QrCode = new Html5Qrcode("reader");
    let selectedFile = null;

    function generateQR() {
        let text = document.getElementById("qrText").value;
        qrContainer.innerHTML = "";
        if (text.trim() === "") {
            alert("Please enter text or URL");
            return;
        }
        new QRCode(qrContainer, {
            text: text,
            width: 200,
            height: 200
        });
    }

    function downloadQR() {
        let img = qrContainer.querySelector("img");
        let canvas = qrContainer.querySelector("canvas");
        if (!img && !canvas) { alert("Generate QR first"); return; }
        let dataURL = canvas ? canvas.toDataURL("image/png") : img.src;
        let a = document.createElement("a");
        a.href = dataURL;
        a.download = "qr-code.png";
        a.click();
    }

    function shareQR() {
        let img = qrContainer.querySelector("img");
        let canvas = qrContainer.querySelector("canvas");
        if (!img && !canvas) { alert("Generate QR first"); return; }
        let dataURL = canvas ? canvas.toDataURL("image/png") : img.src;
        fetch(dataURL)
        .then(res => res.blob())
        .then(blob => {
            let file = new File([blob], "qr.png", { type: "image/png" });
            if (navigator.share) {
                navigator.share({ title: "QR Code", text: "My QR Code", files: [file] });
            } else {
                alert("Sharing not supported");
            }
        });
    }

    function startCameraScan() {
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                html5QrCode.start(
                    devices[0].id,
                    { fps: 10, qrbox: 250 },
                    onScanSuccess
                );
            }
        });
    }

    document.getElementById("fileInput").addEventListener("change", function(event) {
        selectedFile = event.target.files[0];
    });

    function scanSelectedFile() {
        if (!selectedFile) { alert("Please choose an image first!"); return; }
        html5QrCode.scanFile(selectedFile, true)
        .then(decodedText => { onScanSuccess(decodedText); })
        .catch(err => { alert("QR code not detected in image!"); });
    }

    function onScanSuccess(decodedText) {
        let resultDiv = document.getElementById("scanResult");
        if (decodedText.startsWith("http")) {
            resultDiv.innerHTML = "🔗 URL: <a href='" + decodedText + "' target='_blank'>" + decodedText + "</a>";
        } else {
            resultDiv.innerHTML = " Text: " + decodedText;
        }
    }
</script>

</body>
</html>