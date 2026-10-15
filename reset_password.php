<!DOCTYPE html>
<html>
<head>
<title>Reset Password</title>
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
</style>
<body>
<div class="box">
  <h1 style="font-family:'Segoe UI',sans-serif; color:purple;">Reset Password</h1>

  <?php
  include("config.php");

  $token = trim($_GET['token'] ?? '');

  if (empty($token)) {
      echo "<p style='color:red'>&#10060; Invalid or missing token.</p>";
      exit;
  }

  $now  = date("Y-m-d H:i:s");
  $stmt = $conn->prepare("SELECT id FROM users WHERE reset_token = ? AND token_expiry > ?");
  $stmt->bind_param("ss", $token, $now);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 0) {
      echo "<p style='color:red'>&#10060; This link has expired or is invalid. <a href='forgot_password.php' style='color:purple;'>Request a new one</a>.</p>";
      $stmt->close();
      exit;
  }
  $stmt->close();

  if ($_SERVER["REQUEST_METHOD"] === "POST") {
      $new_pass = $_POST['password'] ?? '';
      $confirm  = $_POST['confirm']  ?? '';

      if ($new_pass !== $confirm) {
          echo "<p style='color:red'>&#10060; Passwords do not match.</p>";
      } elseif (strlen($new_pass) < 4) {
          echo "<p style='color:red'>&#10060; Password must be at least 4 characters.</p>";
      } else {
          $safe_pass = mysqli_real_escape_string($conn, $new_pass);
          $upd = $conn->prepare("UPDATE users SET password = ?, reset_token = NULL, token_expiry = NULL WHERE reset_token = ?");
          $upd->bind_param("ss", $safe_pass, $token);
          if ($upd->execute()) {
              echo "<p style='color:green'>&#10003; Password updated! <a href='login.php' style='color:purple;font-weight:bold;'>Login now</a>.</p>";
          } else {
              echo "<p style='color:red'>&#10060; DB error: " . htmlspecialchars($conn->error) . "</p>";
          }
          $upd->close();
          exit;
      }
  }
  ?>

  <form method="POST" action="reset_password.php?token=<?= urlencode($token) ?>">
    <input type="password" name="password" placeholder="New Password (min 4 chars)" minlength="4" required><br>
    <input type="password" name="confirm"  placeholder="Confirm Password"            minlength="4" required><br><br>
    <button type="submit">Reset Password</button>
  </form>

</div>
</body>
</html>