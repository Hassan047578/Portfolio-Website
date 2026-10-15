<?php
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function _configureSMTP(PHPMailer $mail)
{
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'hassanqadri047@gmail.com';
    $mail->Password   = 'clfkmhidfgsrxmfv'; // Gmail App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->Timeout    = 20;
    $mail->setFrom('hassanqadri047@gmail.com', 'Muhammad Hassan');
}

// Called from forgot_password.php
function sendResetEmail($userEmail, $resetLink)
{
    $mail = new PHPMailer(true);
    try {
        _configureSMTP($mail);
        $mail->addAddress($userEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset Request';
        $mail->Body    = '
            <div style="font-family:Segoe UI,sans-serif;max-width:500px;margin:auto;
                        padding:30px;border-radius:12px;background:#f9f4ff;
                        box-shadow:0 0 10px #c084fc;">
                <h2 style="color:purple;">&#128272; Reset Your Password</h2>
                <p>We received a request to reset your password. Click the button below:</p>
                <a href="' . htmlspecialchars($resetLink) . '"
                   style="display:inline-block;margin:20px 0;padding:12px 28px;
                          background:purple;color:white;border-radius:20px;
                          text-decoration:none;font-weight:bold;">
                   Reset Password
                </a>
                <p style="color:#888;font-size:13px;">
                    This link expires in <strong>1 hour</strong>.<br>
                    If you did not request this, ignore this email.
                </p>
                <br><p style="color:#888;font-size:13px;">&#8212; Muhammad Hassan</p>
            </div>';
        $mail->AltBody = "Reset your password here (expires in 1 hour):\n\n$resetLink";
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log(date('Y-m-d H:i:s') . " | RESET MAIL FAILED | " . $mail->ErrorInfo . "\n", 3, __DIR__ . '/mail_error.log');
        return false;
    }
}

// Called from process_login.php
function sendLoginEmail($userEmail)
{
    $mail = new PHPMailer(true);
    try {
        _configureSMTP($mail);
        $mail->addAddress($userEmail);
        $mail->isHTML(true);
        $mail->Subject = 'Login Successful';
        $mail->Body    = '
            <div style="font-family:Segoe UI,sans-serif;max-width:500px;margin:auto;
                        padding:30px;border-radius:12px;background:#f9f4ff;
                        box-shadow:0 0 10px #c084fc;">
                <h2 style="color:purple;">&#10003; Login Successful</h2>
                <p>You logged in to your account successfully.</p>
                <p>If this was not you, please reset your password immediately.</p>
                <br><p style="color:#888;font-size:13px;">&#8212; Muhammad Hassan</p>
            </div>';
        $mail->AltBody = 'You logged in successfully. If this was not you, reset your password immediately.';
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log(date('Y-m-d H:i:s') . " | LOGIN MAIL FAILED | " . $mail->ErrorInfo . "\n", 3, __DIR__ . '/mail_error.log');
        return false;
    }
}