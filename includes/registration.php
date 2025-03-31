<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

if (isset($_POST['signup'])) {
  $token = bin2hex(random_bytes(32));

  $fname = $_POST['fullname'];
  $email = $_POST['emailid'];
  $mobile = $_POST['mobileno'];
  $password = md5($_POST['password']);

  // Check if user already exists
  $sqlCheck = "SELECT COUNT(*) FROM tblusers WHERE EmailId = :email OR ContactNo = :mobile";
  $queryCheck = $dbh->prepare($sqlCheck);
  $queryCheck->bindParam(':email', $email, PDO::PARAM_STR);
  $queryCheck->bindParam(':mobile', $mobile, PDO::PARAM_STR);
  $queryCheck->execute();
  $existingUserCount = $queryCheck->fetchColumn();

  if ($existingUserCount > 0) {
    echo "<script>alert('User with this email or mobile number already exists. Please try again.');</script>";
  } else {
    // Insert new user
    $sql = "INSERT INTO tblusers(FullName, EmailId, ContactNo, Password, token) VALUES(:fname, :email, :mobile, :password, :token)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':fname', $fname, PDO::PARAM_STR);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':mobile', $mobile, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();

    if ($lastInsertId) {
      echo "<script>alert('Check email and Verify Account');</script>";
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
        $mail->addAddress($email, $fname); // Send to the new user's email and name

        // Content
        $mail->isHTML(true);

        $verifyLink = "http://localhost/car-rental/login_verification.php?token=" . $token;
        $mail->Subject = 'Online Car Rental';
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
            <p>Dear "' . $fname . '",</p>
            <p>Thank you for signing up with <strong>Online Car Rental</strong>. Please verify your email address to activate your account and start enjoying our services.</p>
            <div class="email-button">
                <a href="' . $verifyLink . '" target="_blank">Verify Your Email</a>
            </div>
            <p>If you did not sign up for an account, please ignore this email.</p>
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
          echo "Your Verification Email send Successfully. Please check your email and verify your account";
        } else {
          echo "Mailer Error: " . $mail->ErrorInfo;
        }
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
    } else {
      echo "<script>alert('Something went wrong. Please try again');</script>";
    }
  }
}
?>



<script>
  function checkAvailability() {
    $("#loaderIcon").show();
    jQuery.ajax({
      url: "check_availability.php",
      data: 'emailid=' + $("#emailid").val(),
      type: "POST",
      success: function(data) {
        $("#user-availability-status").html(data);
        $("#loaderIcon").hide();
      },
      error: function() {}
    });
  }
</script>
<script type="text/javascript">
  function valid() {
    if (document.signup.password.value != document.signup.confirmpassword.value) {
      alert("Password and Confirm Password Field do not match  !!");
      document.signup.confirmpassword.focus();
      return false;
    }
    return true;
  }
</script>
<div class="modal fade" id="signupform">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h3 class="modal-title">Sign Up</h3>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="signup_wrap">
            <div class="col-md-12 col-sm-6">
              <form method="post" name="signup" onSubmit="return valid();">
                <div class="form-group">
                  <input type="text" class="form-control" name="fullname" placeholder="Full Name" required="required">
                </div>
                <div class="form-group">
                  <input type="text" class="form-control" name="mobileno" placeholder="Mobile Number" id="phone-number" maxlength="12" required="required">
                </div>
                <div class="form-group">
                  <input type="email" class="form-control" name="emailid" id="emailid" onBlur="checkAvailability()" placeholder="Email Address" required="required">
                  <span id="user-availability-status" style="font-size:12px;"></span>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="password" placeholder="Password" required="required">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control" name="confirmpassword" placeholder="Confirm Password" required="required">
                </div>
                <div class="form-group checkbox">
                  <input type="checkbox" id="terms_agree" required="required" checked="">
                  <label for="terms_agree">I Agree with <a href="#">Terms and Conditions</a></label>
                </div>
                <div class="form-group">
                  <input type="submit" value="Sign Up" name="signup" id="submit" class="btn btn-block">
                </div>
              </form>
            </div>

          </div>
        </div>
      </div>
      <div class="modal-footer text-center">
        <p>Already got an account? <a href="#loginform" data-toggle="modal" data-dismiss="modal">Login Here</a></p>
      </div>
    </div>
  </div>
</div>

<script>
    document.getElementById("phone-number").addEventListener("input", function () {
        this.value = this.value.replace(/\D/g, '').slice(0, 12);
    });
</script>