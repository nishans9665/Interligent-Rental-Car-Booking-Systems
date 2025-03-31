<?php
session_start();
include('includes/config.php');
require('includes/connection.php');

// Check if the login form is submitted
if (isset($_POST['login'])) {
	$username = $_POST['username'];
	$password = $_POST['password'];

	$rs = Database::search("SELECT * FROM `admin` WHERE `UserName` = '" . $username . "' AND `Password` = '" . $password . "' ");
	$n = $rs->num_rows;

	if ($n == 1) {
		$data = $rs->fetch_assoc();
		echo "<script type='text/javascript'> document.location = 'dashboard.php'; </script>";
		echo "<script>alert('Done');</script>";
	} else {
		echo "<script>alert('Invalid Details');</script>";
	}
}


?>
<!doctype html>
<html lang="en" class="no-js">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Car Rental Portal | Admin Login</title>
	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
	<link rel="stylesheet" href="css/bootstrap-social.css">
	<link rel="stylesheet" href="css/bootstrap-select.css">
	<link rel="stylesheet" href="css/fileinput.min.css">
	<link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
	<link rel="stylesheet" href="css/style.css">

	<style>
		.container {
			/* display: flex; */
			margin: 0;
			padding: 0;
		}

		.login-page {
			position: absolute;
			/* height: 100%; */
			width: 60%;
			left: 1;
		}

		.form-content {
			position: absolute;
			/* height: 100%; */
			width: 35%;
			right: 0;
			margin-top: 50px;
		}

		.img-responsive {
			height: 100%;
			width: 100%;
			margin-top: 25px;
			margin-bottom: -55px;
			padding: 0;
		}

		.btn_login {
			width: 100%;
			padding: 12px;
			background-color: #C8232A;
			color: white;
			border: none;
			border-radius: 4px;
			font-size: 16px;
			cursor: pointer;
		}
		.btn_login:hover {
			background-color:rgb(112, 111, 111);
		}
		#logintitle{
			font-size: 30px;
		}
	</style>
</head>

<body>

	<div class="container">
    <div class="login-page bk-img" style="background-image: url('img/adimg.jpg'); height: 100vh; background-size: cover; background-position: center;"></div>
    <div class="form-content">
        <!-- <div class="row"> -->
            <div class="col-md-9 ms-auto">
                <img src="img/car-logo1.png" class="img-responsive" alt="">
                <h1 id="logintitle" class="text-center text-bold text-dark mt-3x pb-3x">Login to Your Account</h1>
                <div class="row bk-light">
                    <div class="col-12">
                        <div>
                            <label for="" class="text-sm">Your Username</label>
                            <input type="text" placeholder="Enter your username" id="username" class="form-control mb">
                            <label for="" class="text-sm">Password</label>
                            <input type="password" placeholder="Enter your Password" id="password" class="form-control mb">
                            <button class="btn_login btn-block" name="login" onclick="n_adminSignIn();">LOGIN</button>
                        </div>
                    </div>
                </div>
            </div>
        <!-- </div> -->
    </div>
</div>

	<!-- Loading Scripts -->
	<script src="js/jquery.min.js"></script>
	<script src="js/bootstrap-select.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.dataTables.min.js"></script>
	<script src="js/dataTables.bootstrap.min.js"></script>
	<script src="js/Chart.min.js"></script>
	<script src="js/fileinput.js"></script>
	<script src="js/chartData.js"></script>
	<script src="js/main.js"></script>
	<script src="js/new.js"></script>

</body>

</html>