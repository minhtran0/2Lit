<?php

	include_once global.php;

	if (isset($_POST['submit'])) {
		$success = true;
		$username = trim(htmlspecialchars($_POST['username']));
		$query = "SELECT username FROM lit_user WHERE username = '$username';";
		$data = @mysqli_query($connection, $query);
		if (@mysqli_num_rows($data) > 0) {
			$success = false;
			$_SESSION['usernameError'] = "User name already taken";
		}
		$password = trim(htmlspecialchars($_POST['password']));
		if (strlen($password) < 8) {
			$success = false;
			$_SESSION['passwordError'] = "Password must at least 8 characters long"
		}
		$email = trim($_POST['email']);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$success = false;
		    $_SESSION['emailError'] = "Email address is not valid";
		}

		if ($success) {
			$password = password_hash($password);
			$query = "INSERT INTO lit_user (username, password, email, city, state) VALUES (";
			$query .= "'$username', '$password', '$email', '$city', '$state');";
			$data = @mysqli_query($connection, $query);
			if (!data) {
				die('Database error. Could not enter data. ' . @mysqli_error());
			}
			unset($_POST['username']);
			unset($_POST['password']);
			unset($_POST['email']);
			unset($_POST['city']);
			unset($_POST['state']);

			$query = "SELECT user_id FROM lit_user WHERE username = '$username'";
			$data = @mysqli_query($connection, $query);
			if (@mysqli_num_rows($data) == 1) {
				$_SESSION['userid'] = $data['user_id'];
				$_SESSION['city'] = $data['city'];
				$_SESSION['state'] = $data['state'];
			}

			header("Location: "); // Bring user to city/state index
		}

	}

	@mysqli_close($connection);
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
			<h1 id="heading">2Lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></h1>
		</div>
		<div class="col-md-8" id="contentid"> <!-- The main content column -->
			
			<h2 class="subheading">Create an acccount</h2>

			<div class="row well" id="registrationid">
				<div class="col-md-2"></div>
				<div class="col-md-8">
				<form method="post">								<!-- Registration form start-->
				   <div class="form-group">
				  <label for="">Username</label>
				    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['username'])) {
				  		echo "<div class=\"form-group\">";
				  		echo "<label for=\"usernameError\" class=\"col-sm-4 control-label\"></label>";
				  		echo "<div class=\"alert alert-danger col-md-4\" role=\"alert\">";
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
				    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['email'])) {
				  		echo "<div class=\"form-group\">";
				  		echo "<label for=\"emailError\" class=\"col-sm-4 control-label\"></label>";
				  		echo "<div class=\"alert alert-danger col-md-4\" role=\"alert\">";
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
				    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['password'])) {
				  		echo "<div class=\"form-group\">";
				  		echo "<label for=\"passwordError\" class=\"col-sm-4 control-label\"></label>";
				  		echo "<div class=\"alert alert-danger col-md-4\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['passwordError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['passwordError']);
				  	}
				  ?>
				  <div class="form-group">
				  <label for="">City</label>
				    <input type="text" class="form-control" id="city" name="city" placeholder="City" required>
				  </div>
				  <div class="form-group">
				  <label for="">State</label>
				    <input type="text" class="form-control" id="state" name="state" placeholder="state" required>
				  </div>
				  <button type="submit" class="btn btn-default">Submit</button>
				</form>							<!-- Registration form END-->
				</div>
				<div class="col-md-2"></div>
			</div>
		</div>								<!-- End main content column-->
		<div class="col-md-4" id="sidebarid"> <!-- The sidebar -->
			<div class="panel panel-default">
				<div class="panel-heading" id="sidebar-header">Sidebar  <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></div>
				<div class="panel-body">
					<p></p>
					<br><br><br><br><br><br><br><br><br>
				</div>
			</div>
		</div>								<!-- End sidebar-->
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>