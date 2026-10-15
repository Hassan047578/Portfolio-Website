<!DOCTYPE html>
<html>
<head>
<title>Login</title>
<!-- Google Sign-In SDK -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
</head>
<style>
body{
 background: radial-gradient(circle, rgba(238, 174, 202, 1) 0%, rgba(148, 187, 233, 1) 100%);
}
.box{
    width:350px;
    margin:150px auto;
    padding:20px;
    background:transparent;
    text-align:center;
    box-shadow:0 0 10px purple;
    border:1ps solid purple;
    border-radius:20px;
}
input{
    width:90%;
    padding:10px;
    margin:10px;
    border-radius:20px;
    background-color:white;
    border:1px solid white;
}
button{
    width:95%;
    padding:10px;
    border-radius:20px;
    background:white;
    color:purple;
    border:1px solid white;
}
button:hover{
    color:white;
    background-color:#CBC3E3;
}
.lnk{
    text-decoration:none;
    font-size:20px;
}
.lnk:hover{
    color:white;
}
#lgn {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Divider styling */
.divider {
    display: flex;
    align-items: center;
    margin: 10px 10px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #555;
}
.divider::before,
.divider::after {
    content: '';
    flex: 1;
    border-bottom: 1px solid #aaa;
}
.divider span {
    padding: 0 10px;
    font-size: 14px;
}

/* Google button styling */
.google-btn {
    width: 95%;
    padding: 10px;
    border-radius: 20px;
    background: white;
    color: #444;
    border: 1px solid #ddd;
    cursor: pointer;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    font-size: 15px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin: 0 auto;
    transition: background-color 0.2s;
}
.google-btn:hover {
    background-color: #f1f1f1;
    color: #444;
}
.google-btn img {
    width: 20px;
    height: 20px;
}
</style>
<body>
<div class="box">
    <h1 id="lgn">Login</h1>

    <form action="process/login_process.php" method="POST">
        <input type="email" name="email" placeholder="Email" required><br><br>
        <input type="password" name="password" placeholder="Password" required><br><br>
        <button type="submit">Login</button>
        <br>
   <a class="lnk" href="forgot_password.php" style="font-size:15px;">Forgot Password?</a>
    </form>

    <br>

    <!-- Divider -->
    <div class="divider"><span>or</span></div>

    <br>

    <!-- Google Login Button -->
    <button class="google-btn" onclick="googleLogin()">
        <img src="https://developers.google.com/identity/images/g-logo.png" alt="Google logo">
        Login with Google
    </button>

    <br><br>
    <a class="lnk" href="signup.php">Create account</a>
</div>

<script>
    // ⬇️ PASTE YOUR GOOGLE CLIENT ID HERE
    const GOOGLE_CLIENT_ID = "157720749000-519h0elp94ouibcppo4bbgsjngakd278.apps.googleusercontent.com";

    function googleLogin() {
        const client = google.accounts.oauth2.initTokenClient({
            client_id: GOOGLE_CLIENT_ID,
            scope: "openid email profile",
            callback: (tokenResponse) => {
                if (tokenResponse.access_token) {
                    // Get user info from Google
                    fetch("https://www.googleapis.com/oauth2/v3/userinfo", {
                        headers: { Authorization: "Bearer " + tokenResponse.access_token }
                    })
                    .then(res => res.json())
                    .then(userInfo => {
                        // Send user info to your PHP backend
                        const form = document.createElement("form");
                        form.method = "POST";
                        form.action = "process/login_process.php";

                        const fields = {
                            google_login: "1",
                            google_email: userInfo.email,
                            google_name: userInfo.name
                        };

                        for (const key in fields) {
                            const input = document.createElement("input");
                            input.type = "hidden";
                            input.name = key;
                            input.value = fields[key];
                            form.appendChild(input);
                        }

                        document.body.appendChild(form);
                        form.submit();
                    })
                    .catch(() => alert("Google login failed. Please try again."));
                }
            }
        });
        client.requestAccessToken();
    }
</script>
</body>
</html>