
<!-- Page need to be updated for security -->


<?php

	include_once "global.php";

	if (isset($_SESSION['userid'])) {
		header("Location: ");	// Bring the user to the college/state 2Lit page
	}

	if (isset($_POST['submit'])) {
		$username = htmlspecialchars(trim($_POST['username']));
		$password = htmlspecialchars(trim($_POST['password']));

		$query = "SELECT * FROM lit_user WHERE username = '$username'";
		$data = @mysqli_query($connection, $query);

		$success = false;
		if (@mysqli_num_rows($data) == 1) {
			if (password_verify($password, $data['password'])) {
				success = true;
			}
		}

		if ($success) {
			$_SESSION['userid'] = $username;
			$_SESSION['college'] = $data['college'];

			header("Location ");		// Bring the user to the college 2Lit page
		}
		else {
			$_SESSION['error'] = "Your username or password is incorrect".
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

			<h2 class="subheading">Sign in into your account</h2>

			<div class="row well" id="contentid">

				<div class="col-md-2"></div>
				<div class="col-md-8">	

				<form method="post">								<!-- Registration form start-->
				   <div class="form-group">
				   <?php
				  	if (isset($_SESSION['error'])) {
				  		echo "<div class=\"form-group\">";
				  		echo "<label for=\"error\" class=\"col-sm-4 control-label\"></label>";
				  		echo "<div class=\"alert alert-danger col-md-4\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['error'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['error']);
				  	}
				  ?>
				  <label for="">Username</label>
				    <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
				  </div>
				  <div class="form-group">
				    <label for="password">Password</label>
				    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
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