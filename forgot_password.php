<!DOCTYPE html>
<html>
<head>
<title>Forgot Password</title>
</head>
<style>
body {
  background: radial-gradient(circle, rgba(238,174,202,1) 0%, rgba(148,187,233,1) 100%);
}
.box {
  width: 350px;
  margin: 150px auto;
  padding: 20px;
  text-align: center;
  box-shadow: 0 0 10px purple;
  border-radius: 20px;
  background: rgba(255,255,255,0.55);
  backdrop-filter: blur(6px);
}
input {
  width: 90%;
  padding: 10px;
  margin: 10px;
  border-radius: 20px;
  background-color: white;
  border: 1px solid white;
  outline: none;
  font-size: 14px;
}
button {
  width: 95%;
  padding: 10px;
  border-radius: 20px;
  background: white;
  color: purple;
  border: 1px solid white;
  cursor: pointer;
  font-size: 15px;
  font-weight: bold;
}
button:hover { background-color: #CBC3E3; color: white; }
.msg { margin-top: 14px; font-size: 14px; color: green; }
.err { color: red; }
</style>
<body>
<div class="box">
  <h1 style="font-family:'Segoe UI',sans-serif; color:purple;">Forgot Password</h1>
  <p style="font-size:14px; color:#555;">Enter your email — we'll send you a reset link.</p>

  <?php
  include("config.php");
  include("sendmail.php");   // ← matches your actual filename (no underscore)

  if ($_SERVER["REQUEST_METHOD"] === "POST") {

      $email = trim($_POST['email'] ?? '');

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
          echo "<p class='msg err'>&#10060; Please enter a valid email address.</p>";
      } else {

          $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
          $stmt->bind_param("s", $email);
          $stmt->execute();
          $stmt->store_result();

          if ($stmt->num_rows === 1) {

              $token  = bin2hex(random_bytes(32));
              $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

              $upd = $conn->prepare("UPDATE users SET reset_token = ?, token_expiry = ? WHERE email = ?");
              $upd->bind_param("sss", $token, $expiry, $email);

              if ($upd->execute()) {
                  $reset_link = "http://hassanwazir.online/reset_password.php?token=" . urlencode($token);
                  $sent = sendResetEmail($email, $reset_link);

                  if ($sent) {
                      echo "<p class='msg'>&#10003; Reset link sent to <b>" . htmlspecialchars($email) . "</b>! Check inbox &amp; spam folder.</p>";
                  } else {
                      echo "<p class='msg err'>&#9888; Email failed to send. Check mail_error.log on server.</p>";
                      if (file_exists('mail_error.log')) {
                          $lines = file('mail_error.log');
                          $last  = end($lines);
                          echo "<p style='font-size:12px;color:red;background:#fff3cd;padding:8px;border-radius:6px;'><b>Error:</b> " . htmlspecialchars($last) . "</p>";
                      }
                  }
              } else {
                  echo "<p class='msg err'>&#10060; DB error: " . htmlspecialchars($conn->error) . "</p>";
              }
              $upd->close();

          } else {
              echo "<p class='msg err'>&#10060; No account found with that email address.</p>";
          }

          $stmt->close();
      }
  }
  ?>

  <form method="POST" action="forgot_password.php">
    <input type="email" name="email" placeholder="Your Email" required autocomplete="email"><br><br>
    <button type="submit">Send Reset Link</button>
  </form>
  <br>
  <a href="login.php" style="text-decoration:none; color:purple; font-size:14px;">&#8592; Back to Login</a>
</div>
</body>
</html>