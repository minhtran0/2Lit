<?php

	include_once "global.php";

	if (!isset($_SESSION['userid'])) {
		header("Location: signin.php");	
	}

	$query = "SELECT city, state FROM lit_cities WHERE city_id = '".$_SESSION['cityid']."'";
	$result = $conn->query($query);
	if ($result->num_rows != 1)
		$success = false;
	else {
		$data = $result->fetch_assoc();
		$city = $data['city'];
		$state = $data['state'];
	}

	if (isset($_POST['submit'])) {
		$success = true;
		$title = trim($_POST['title']);
		$date = $_POST['date_event'];
		$location = trim($_POST['location']);
		$start_time = $_POST['start_time'];
		$end_time = $_POST['end_time'];
		$description = trim($_POST['description']);
		if (strlen($title) > 40) {
			$success = false;
			$_SESSION['titleError'] = " You wrote " . strlen($title) . " characters. 40 characters maximum";
		}
		if (strlen($location) > 40) {
			$success = false;
			$_SESSION['locationError'] = " You wrote " . strlen($location) . " characters. 40 characters maximum";
		}
		if (strlen($description) > 1000) {
			$success = false;
			$_SESSION['descriptError'] = " You wrote " . strlen($description) . " characters. 1000 characters maximum";
		}
		if ($start_time >= $end_time) {
			$success = false;
			$_SESSION['timeError'] = " End time must be after Start time";
		}
		$today_start = strtotime('today');
		$today_end = strtotime('tomorrow');
		$date_timestamp = strtotime($date);

		if ($date_timestamp < $today_start) {
		    $success = false;
			$_SESSION['dateError'] = " Cannot be a past date";
		}

		if ($success) {
			$query = "INSERT INTO lit_post (title, user_id, description, datetime_posted, date_event, starttime_event, endtime_event, location, upvotes, downvotes, city_id) VALUES ";
			if ($stmt = $conn->prepare($query." (?, '".$_SESSION['userid']."', ?, NOW(), '$date', '$start_time', '$end_time', ?, '1', '0', '".$_SESSION['cityid']."')")) {
				$stmt->bind_param('sss', $title, $description, $location);
				$stmt->execute();
				$postid = $stmt->insert_id;
				$stmt->close();
			}

			header("Location: view.php?city=".$_SESSION['cityid']."&sort=hot");
		}
	}

	$conn->close();

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
	<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand lit-heading" href=" <?php echo "view.php?city=".$_SESSION['cityid']."&sort=hot";?> ">2Lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active"><a href="submit.php">Submit a post</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php if (isset($_SESSION['username'])) echo $_SESSION['username']; ?> <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="#">Profile</a></li>
                <li><a href="#">Settings</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="#">Privacy Policy</a></li>
                <li><a href="#">Terms and Conditions</a></li>
                <li role="separator" class="divider"></li>
                <li><a href="logout.php">Sign out</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<div class="container">

	<br><br><br>

		<div class="col-md-8" id="contentid"> <!-- The main content column -->

			<h2 class="subheading">Submit an post to <?php echo $city.", ".$state; ?> </h2>

			<div class="row well" id="contentid">

				<div class="col-md-2"></div>
				<div class="col-md-8">	


				<form method="post">								<!-- Submit form start-->
				  <div class="form-group">
				  <label for="title">Title</label>
				    <input type="text" class="form-control" id="title" name="title" placeholder="Title" value="<?php if (isset($_POST['title'])) echo $_POST['title'];?>" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['titleError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['titleError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['titleError']);
				  	}
				  ?>
				  <div class="form-group">
				    <label for="date_event">Date of event</label>
				    <input type="date" class="form-control" id="date_event" name="date_event" placeholder="Date" value="<?php if (isset($_POST['date_event'])) echo $_POST['date_event']; else echo date('Y-m-d');?>" required>
				  </div>
				  <?php
				  	if (isset($_SESSION['dateError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['dateError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['dateError']);
				  	}
				  ?>
				  <div class="form-group">
				  <label for="title">Location of event</label>
				    <input type="text" class="form-control" id="location" name="location" placeholder="123 Main St." value="<?php if (isset($_POST['location'])) echo $_POST['location'];?>" required>
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
				  <div class="form-horizontal">
					  <div class="col-sm-5">
						  <div class="form-group">
						    <label for="start_time">Time event starts</label>
						    <input type="time" class="form-control" id="start_time" name="start_time" placeholder="" value="<?php if (isset($_POST['start_time'])) echo $_POST['start_time'];?>" required>
						  </div>
					  </div>
					  <div class="col-sm-2"></div>
					  <div class="col-sm-5">
						  <div class="form-group">
						    <label for="end_time">Time event ends</label>
						    <input type="time" class="form-control" id="end_time" name="end_time" placeholder="" value="<?php if (isset($_POST['end_time'])) echo $_POST['end_time'];?>" required>
						  </div>
					  </div>
				  </div>
				  <?php
				  	if (isset($_SESSION['timeError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['timeError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['timeError']);
				  	}
				  ?>
				  <div class="form-group">
				    <label for="description">Description of event</label>
				 	<textarea class="form-control" maxlength="1000" id="description" name="description" rows="7" required><?php if (isset($_POST['description'])) echo $_POST['description'];?></textarea>
				  </div>
				  <?php
				  	if (isset($_SESSION['descriptError'])) {
				  		echo "<div class=\"form-group error-box\">";
				  		echo "<div class=\"alert alert-danger\" role=\"alert\">";
						echo "<span class=\"glyphicon glyphicon-exclamation-sign\" aria-hidden=\"true\"></span>";
						echo "<span class=\"sr-only\">Error:</span>";
						echo  $_SESSION['descriptError'];
						echo "</div>";
						echo "</div>";
						unset($_SESSION['descriptError']);
				  	}
				  ?>
				  <button type="submit" id="submit" name="submit" class="btn btn-default">Submit</button>
				</form>							<!-- Submit form END-->
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