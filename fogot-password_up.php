<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (isset($_GET['token'])) {
  $token = $_GET['token'];

  if (isset($_POST['update'])) {
    $password=md5($_POST['newpassword']); 
    $newpassword = $_POST['newpassword'];
    $confirmpassword = $_POST['confirmpassword'];

    // Check if passwords match
    if ($newpassword != $confirmpassword) {
      $error = "Passwords do not match!";
    } else {
      // Hash the new password before saving
      // $hashedPassword = password_hash($newpassword, PASSWORD_BCRYPT);

      // Update the password in the database based on the token
      $sql = "UPDATE tblusers SET Password = :password WHERE token = :token";
      $query = $dbh->prepare($sql);

      $query->bindParam(':password',$password,PDO::PARAM_STR);
      $query->bindParam(':token', $token, PDO::PARAM_STR);
      $query->execute();

      
      if ($query->rowCount() > 0) {
        // Redirect to the home page after updating the password
        header('Location: index.php');
        exit; // Ensure no further code is executed
    } else {
        $error = "There was an issue updating your password.";
    }
      
      
    }}

?>
  <!DOCTYPE HTML>
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="keywords" content="">
    <meta name="description" content="">

    <title>CarForYou - Responsive Car Dealer HTML5 Template</title>

    <!-- Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!-- Custom Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!-- OWL Carousel slider -->
    <link rel="stylesheet" href="assets/css/owl.carousel.css" type="text/css">
    <link rel="stylesheet" href="assets/css/owl.transitions.css" type="text/css">
    <!-- slick-slider -->
    <link href="assets/css/slick.css" rel="stylesheet">
    <!-- bootstrap-slider -->
    <link href="assets/css/bootstrap-slider.min.css" rel="stylesheet">
    <!-- FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">

    <!-- SWITCHER -->
    <link rel="stylesheet" id="switcher-css" type="text/css" href="assets/switcher/css/switcher.css" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/red.css" title="red" media="all" data-default-color="true" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/orange.css" title="orange" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/blue.css" title="blue" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/pink.css" title="pink" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/green.css" title="green" media="all" />
    <link rel="alternate stylesheet" type="text/css" href="assets/switcher/css/purple.css" title="purple" media="all" />

    <!-- Fav and touch icons -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/images/favicon-icon/apple-touch-icon-144-precomposed.png">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/images/favicon-icon/apple-touch-icon-114-precomposed.html">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/images/favicon-icon/apple-touch-icon-72-precomposed.png">
    <link rel="apple-touch-icon-precomposed" href="assets/images/favicon-icon/apple-touch-icon-57-precomposed.png">
    <link rel="shortcut icon" href="assets/images/favicon-icon/favicon.png">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700,900" rel="stylesheet">

    <style>
      .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }

      .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }
    </style>
  </head>

  <body>

    <!-- Header -->
    <?php include('includes/header.php'); ?>

    <!-- Page Header -->
    <section class="page-header profile_page">
      <div class="container">
        <div class="page-header_wrap">
          <div class="page-heading">
            <h1>Update Password</h1>
          </div>
          <ul class="coustom-breadcrumb">
            <li><a href="#">Home</a></li>
            
          </ul>
        </div>
      </div>
      <!-- Dark Overlay-->
      <div class="dark-overlay"></div>
    </section>

    <!-- Profile Section -->
    <section class="user_profile inner_pages">
      <div class="container" style="width: 50%;">

        <div class="col-md-12 col-sm-12">
          <div class="">
            <form name="chngpwd" method="post" onSubmit="return valid();">
              <div class="gray-bg field-title">
                <h6>Update password</h6>
              </div>
              <?php if ($error) { ?>
                <div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div>
              <?php } else if ($msg) { ?>
                <div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div>
              <?php } ?>


              <div class="form-group">
                <label class="control-label">Password</label>
                <input class="form-control white_bg" id="newpassword" type="password" name="newpassword" required>
              </div>

              <div class="form-group">
                <label class="control-label">Confirm Password</label>
                <input class="form-control white_bg" id="confirmpassword" type="password" name="confirmpassword" required>
              </div>

              <div class="form-group">
                <input type="submit" value="Update" name="update" id="submit" class="btn btn-block">
              </div>
            </form>
          </div>
        </div>



      </div>
    </section>

    <!-- Footer -->
    <?php include('includes/footer.php'); ?>

    <!-- Back to top -->
    <div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>

    <!-- Scripts -->
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/interface.js"></script>
    <!-- Switcher -->
    <script src="assets/switcher/js/switcher.js"></script>
    <!-- Bootstrap Slider JS-->
    <script src="assets/js/bootstrap-slider.min.js"></script>
    <!-- Slider JS-->
    <script src="assets/js/slick.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>

  </body>

  </html>

<?php } ?>