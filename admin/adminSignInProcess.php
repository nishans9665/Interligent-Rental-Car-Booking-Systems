<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';
session_start();
include('includes/config.php');
require('includes/connection.php');

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $rs = Database::search("SELECT * FROM `admin` WHERE `UserName` = '" . $username . "' AND `Password` = '" . $password . "' ");
    $n = $rs->num_rows;

    if ($n == 1) {

        $token = bin2hex(random_bytes(32));
        $data = $rs->fetch_assoc();
        $adminmail = $data["email"];

        Database::iud("UPDATE `admin` SET `token` = '" . $token . "' WHERE `email` = '" . $adminmail . "' ");

        $mail = new PHPMailer(true);


        // Server settings
        $mail->SMTPDebug = 0; // Set to 2 for debugging in development
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Correct SMTP server for Gmail
        $mail->SMTPAuth = true;
        $mail->Username = 'duleeshasavindi@gmail.com'; // Use environment variable
        $mail->Password = 'xjsdnixxdelpmtfn'; // Use environment variable
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Recipients
        $mail->setFrom('duleeshasavindi@gmail.com', 'Online Car Rental'); // Replace with your name or app name
        $mail->addAddress($adminmail, 'Admin'); // Send to the new user's email and name

        // Content
        $mail->isHTML(true);

        $verifyLink = "http://localhost/car-rental/admin/admin_email_verification.php?token=" . $token;
        $mail->Subject = 'Online Car Rental - Admin Signin Verification';
        $mail->Body = '

            <!DOCTYPE html>
<html>
<head>
    <title>Admin Sign-In Verification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background-color: #ff0000;
            color: #ffffff;
            text-align: center;
            padding: 20px 10px;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
        }
        .email-body {
            padding: 20px 30px;
            line-height: 1.6;
            text-align: center;
        }
        .email-body p {
            margin: 10px 0;
        }
        .email-button {
            margin-top: 20px;
        }
        .email-button a {
            background-color: #000000;
            color: #ffffff;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 16px;
            font-weight: bold;
            border-radius: 5px;
            display: inline-block;
        }
        .email-footer {
            background-color: #f1f1f1;
            text-align: center;
            padding: 15px 10px;
            font-size: 12px;
            color: #666;
        }
        .email-footer p {
            margin: 5px 0;
        }
        @media (max-width: 600px) {
            .email-body {
                padding: 15px 20px;
            }
            .email-header h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Online Car Rental</h1>
        </div>
        <div class="email-body">
            <p>Dear Admin,</p>
            <p>A sign-in attempt was made using your credentials. Please verify this action by clicking the button below:</p>
            <div class="email-button">
                <a href="' . $verifyLink . '" target="_blank">Verify Sign-In</a>
            </div>
            <p>If you did not attempt to sign in, please contact support immediately.</p>
            <p>Best Regards,</p>
            <p><strong>Online Car Rental Team</strong></p>
        </div>
        <div class="email-footer">
            <p>&copy; 2024 Online Car Rental. All rights reserved.</p>
            <p>Need help? Contact us at <a href="mailto:support@onlinecarrental.com">support@onlinecarrental.com</a>.</p>
        </div>
    </div>
</body>
</html>

                ';
        

        // Send the email
        if ($mail->send()) {
            echo "Admin verification email sent successfully. Please check your email and verify.";
        } else {
            echo "Mailer Error: " . $mail->ErrorInfo;
        }
    } else {
        echo "<script>alert('Something went wrong. Please try again');</script>";
    }
}
