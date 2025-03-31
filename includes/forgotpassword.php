<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require 'vendor/autoload.php';

if (isset($_POST['update'])) {
  $email = $_POST['email'];
  $mobile = $_POST['mobile'];

  $token = bin2hex(random_bytes(32));

  $sql = "SELECT EmailId FROM tblusers WHERE EmailId=:email and ContactNo=:mobile";
  $query = $dbh->prepare($sql);
  $query->bindParam(':email', $email, PDO::PARAM_STR);
  $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);

  $query->execute();
  $results = $query->fetchAll(PDO::FETCH_OBJ);
  if ($query->rowCount() > 0) {
    $success = true;

    $updateSql = "UPDATE tblusers SET token=:token WHERE EmailId=:email";
    $updateQuery = $dbh->prepare($updateSql);
    $updateQuery->bindParam(':email', $email, PDO::PARAM_STR);
    $updateQuery->bindParam(':token', $token, PDO::PARAM_STR);
    $updateQuery->execute();

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
      $mail->setFrom('duleeshasavindi@gmail.com', 'Online Car Rental'); // Replace with your name or app name
      $mail->addAddress($email, 'User'); // Adjust recipient details

      // Content
      $mail->isHTML(true);

      $verifyLink = "http://localhost/car-rental/fogot-password_up.php?token=" . $token;
      $mail->Subject = 'Online Car Rental - Reset Password';
      $mail->Body = '

      <!DOCTYPE html>
<html>
<head>
    <title>Password Reset Request</title>
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
            <h1>Password Reset Request</h1>
        </div>
        <div class="email-body">
            <p>We received a request to reset your password for your <strong>Online Car Rental</strong> account. To proceed, please click the button below:</p>
            <div class="email-button">
                <a href="' . $verifyLink . '" target="_blank">Reset Your Password</a>
            </div>
            <p>If you did not request a password reset, please ignore this email. Your account is secure.</p>
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
        echo "Email sent successfully";
      } else {
        echo "Mailer Error: " . $mail->ErrorInfo;
      }
    } catch (Exception $e) {
      echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  } else {
    echo "<script>alert('Email id or Mobile no is invalid');</script>";
  }
}

?>
<script type="text/javascript">
  function valid() {
    if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
      alert("New Password and Confirm Password Field do not match  !!");
      document.chngpwd.confirmpassword.focus();
      return false;
    }
    return true;
  }

  window.onload = function() {
    const successTrigger = document.getElementById('successTrigger').value;
    if (successTrigger === 'true') {
      $('#newModal').modal('show');
      successTrigger.value = 'false'; // Show the new modal
    }
  };

  function handleModalClose() {
    console.log("Modal closed");
    // Add custom logic here, such as clearing form fields or performing cleanup
  }
</script>


<div class="modal fade" id="forgotpassword">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Password Recovery</h3>
      </div>
      <div class="modal-body">
        <form name="chngpwd" method="post" onSubmit="return valid();">
          <div class="form-group">
            <input type="email" name="email" class="form-control" placeholder="Your Email address*" required="">
          </div>
          <div class="form-group">
            <input type="text" name="mobile" class="form-control" placeholder="Your Reg. Mobile*" required="">
          </div>
          <div class="form-group">
            <input type="submit" value="Reset My Password" name="update" class="btn btn-block">
          </div>

          <!-- Hidden input field to track success -->
          <input type="hidden" id="successTrigger" value="<?php echo $success ? 'true' : 'false'; ?>">

          <div class="text-center">
            <p class="gray_text">For security reasons we don't store your password. Your password will be reset and a new one will be sent.</p>
            <p><a href="#loginform" data-toggle="modal" data-dismiss="modal"><i class="fa fa-angle-double-left" aria-hidden="true"></i> Back to Login</a></p>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="newModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Password Recovery</h3>
      </div>
      <div class="modal-body">
        <p>We've sent a verification email to your registered address. Please check your inbox and spam folder. Follow the link provided to verify your email and complete the process.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="handleModalClose();">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="resetPasswordModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="modal-title">Reset Your Password</h3>
      </div>
      <div class="modal-body">
        <form action="reset_password.php" method="post">
          <div class="form-group">
            <input type="password" name="newpassword" class="form-control" placeholder="New Password" required="">
          </div>
          <div class="form-group">
            <input type="password" name="confirmpassword" class="form-control" placeholder="Confirm New Password" required="">
          </div>
          <div class="form-group">
            <input type="submit" value="Reset Password" name="reset" class="btn btn-block">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>