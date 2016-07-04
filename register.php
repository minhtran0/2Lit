<?php

	include_once "global.php";

	if (isset($_SESSION['userid']) && isset($_SESSION['cityid'])) {
		header("Location: view.php?city=".$_SESSION['cityid']."&sort=hot");
	}

	if (isset($_POST['submit'])) {	
		$success = true;
		$username = strtolower($_POST['username']);
		if (!preg_match('/^[A-Za-z0-9_]+$/', $username)) {
			$success = false;
			$_SESSION['usernameError'] = " Username must be alpha-numeric or '_'";
		}
		if (strlen($username) < 6 && strlen($username) > 20) {
			$success = false;
			$_SESSION['usernameError'] = " Username must be 6-20 characters long";
		}
		if ($success) {
			$query = "SELECT username FROM lit_user WHERE username = '$username'";
			$data = $conn->query($query);
			if (!data)	printf("Errormessage: %s\n", $conn->error);
			if ($data->num_rows > 0) {
				$success = false;
				$_SESSION['usernameError'] = " Username is already taken";
			}
		}
		
		$password = $_POST['password'];
		$password_again = $_POST['password_again'];
		if (strlen($password) < 8) {
			$success = false;	
			$_SESSION['passwordError'] = " Password must at least 8 characters long";
		}
		if ($password != $password_again) {
			$success = false;
			$_SESSION['passwordError'] = " Passwords do not match";
		}
		$email = trim($_POST['email']);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$success = false;
		    $_SESSION['emailError'] = " Email address is not valid";
		}
		if ($success) {
			$query = "SELECT email FROM lit_user WHERE email = '$email'";
			$data = $conn->query($query);
			if (!data)	printf("Errormessage: %s\n", $conn->error);
			if ($data->num_rows > 0) {
				$success = false;
				$_SESSION['emailError'] = " Email is already used";
			}
		}

		// Find city/state
		$city = strtolower(trim($_POST['city']));
		$state = strtolower(trim($_POST['state']));
		if (!preg_match('/^[a-z\d\-_\s]+$/i', $city)) {
			$success = false;
			$_SESSION['locationError'] = " Location must be alpha-numeric";
		}
		if (!preg_match('/^[a-z\d\-_\s]+$/i', $state)) {
			$success = false;
			$_SESSION['locationError'] = " Location must be alpha-numeric";
		}
		if($success) {
			$query = "SELECT city_id FROM lit_cities WHERE city = '$city' AND state = '$state'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			if (!data)	printf("Errormessage: %s\n", $conn->error);

			if ($result->num_rows > 0) {
				$city_id = $data['city_id'];
			}
			else {
				$query = "INSERT INTO lit_cities (city, state) VALUES ('$city', '$state')";
				$conn->query($query);
				if (!data)	printf("Errormessage: %s\n", $conn->error);
				$query = "SELECT city_id FROM lit_cities WHERE city = '$city' AND state = '$state'";
				$result = $conn->query($query);
				$data = $result->fetch_assoc();
				if (!data)	printf("Errormessage: %s\n", $conn->error);
				$city_id = $data['city_id'];
			}
		}

		if ($success) {
			$passwordhash = sha1($password);		// This has to be replaced. SHA1 is not a strong hash.

			$query = "INSERT INTO lit_user (username, password, email, city_id) VALUES ";
			$query .= "('$username', '$passwordhash', '$email', '$city_id')";
			$conn->query($query);

			unset($_POST['username']);
			unset($_POST['password']);
			unset($_POST['email']);
			unset($_POST['city']);
			unset($_POST['state']);

			$query = "SELECT user_id, username, city_id FROM lit_user WHERE username = '$username'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			if ($result->num_rows == 1) {
				$_SESSION['userid'] = $data['user_id'];
				$_SESSION['cityid'] = $data['city_id'];
				$_SESSION['username'] = $data['username'];
			}

			header("Location: view.php?city=".$_SESSION['cityid']."&sort=hot");
		}

	}

	$conn->close();;
?>


<html>
<head>
	<title>2Lit</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.min.css"></link>
	<link rel="stylesheet" href="css/style.css"></link>
	<link href='http://fonts.googleapis.com/css?family=Overlock' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Chivo' rel='stylesheet' type='text/css'>
		
	</style>
</head>
<body>
	<div class="container">
		<div class="row page-header">
			<a href="index.php"><h1 class="lit-heading" id="heading">2Lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></h1></a>
		</div>
		<div class="col-md-8" id="contentid"> <!-- The main content column -->
			
			<h2 class="subheading">Create an acccount</h2>

			<div class="row well" id="registrationid">
				<div class="col-md-2"></div>
				<div class="col-md-8">
				<form method="post">								<!-- Registration form start-->
				   <div class="form-group">
				  <label for="">Username</label>
				    <input type="text" class="form-control" id="username" name="username" placeholder="Username" method="post" value="<?php if (isset($_POST['username'])) echo $_POST['username'];?>" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['usernameError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['usernameError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['usernameError']);
				  	}
				  ?>
				  <div class="form-group">
				    <label for="email">Email address</label>
				    <input type="email" class="form-control" id="email" name="email" placeholder="Email" method="post" value="<?php if (isset($_POST['email'])) echo $_POST['email'];?>" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['emailError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['emailError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['emailError']);
				  	}
				  ?>
				  <div class="form-group">
				    <label for="password">Password</label>
				    <input type="password" class="form-control" id="password" name="password" placeholder="Password" method="post" required>
				  </div>
				  <div class="form-group">
				    <label for="password_again">Enter your password again</label>
				    <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Password" method="post" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['passwordError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['passwordError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['passwordError']);
				  	}
				  ?>
				  <div class="form-horizontal">
					  <div class="col-sm-5">
						  <div class="form-group">
						    <label for="city">City</label>
						    <input type="text" class="form-control" id="city" name="city" placeholder="City" method="post" value="<?php if (isset($_POST['city'])) echo $_POST['city'];?>" required>
						  </div>
					  </div>
					  <div class="col-sm-2"></div>
					  <div class="col-sm-5">
						  <div class="form-group">
						    <label for="state">State</label>
						    <input type="text" class="form-control" id="state" name="state" placeholder="State" method="post" value="<?php if (isset($_POST['state'])) echo $_POST['state'];?>" required>
						  </div>
					  </div>
				  </div>
				  <?php
				  	if (isset($_SESSION['locationError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['locationError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['locationError']);
				  	}
				  ?>
				  <button type="submit" class="btn btn-default" name="submit" id="submit" method="post">Submit</button>
				</form>							<!-- Registration form END-->
				</div>
				<div class="col-md-2"></div>
			</div>
		</div>								<!-- End main content column-->
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>