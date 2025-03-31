<?php

session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

$username = $_POST['username'];
$email = $_POST['email'];
$password = $_POST['password'];


$mail = new PHPMailer(true);

try {
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
    $mail->setFrom('duleeshasavindi@gmail.com', 'Thara Fashion'); // Replace with your name or app name
    $mail->addAddress($email, 'Admin Invitation'); // Adjust recipient details

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Online Car Rental - Admin Invitation';
    $mail->Body = '
    <!DOCTYPE html>
<html>
<head>
    <title>Welcome to Online Car Rental</title>
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
            margin: 20px 0;
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
            <h1>Welcome to Online Car Rental</h1>
        </div>
        <div class="email-body">
            
            <p>We are excited to inform you that you have been added as an Admin to the <strong>Online Car Rental</strong> system.</p>
            <p>You can now sign in to the system using the credentials provided to you:</p>
            <div class="email-button">
                <a href="http://localhost/car-rental/admin/" target="_blank">Sign In to Admin Portal</a>
            </div>
            <p>If you have any questions or need assistance, please do not hesitate to reach out to our support team.</p>
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
    $mail->send();
    if ($mail->send()) {
        echo "Admin Invitaion Email sent successfully";
    } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
