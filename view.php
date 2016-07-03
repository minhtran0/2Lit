<?php

	include_once "global.php";

	$canPost = false;
	$canVote = false;

	$success = true;
	if (isset($_GET['city']))
		$cityid = $_GET['city'];
	// Else default to user's city
	else if (isset($_SESSION['userid'])) {
		$query = "SELECT cityid FROM lit_user WHERE userid = '$_SESSION['userid']'";
		$result = $conn->query($query);
		$data = $result->fetch_assoc();
		$cityid = $data['city_id'];
		header("Location: view.php?city=$cityid&sort=1");
	}

	if (!preg_match('/^[1-9][0-9]*$/', $cityid)) {
		$success = false;
		header("Location: index.php");
	}
	if (isset[$_GET['sort']] && preg_match('/^[1-9][0-9]*$/', $_GET['sort'])) {
		$sort = strtolower($_GET['sort']);
	}
	else
		$sort = "hot";

	// If user is logged in and is in the same city -- then they can post and vote
	if (isset($_SESSION['userid']) && $_SESSION['cityid'] == $cityid) {
		$canPost = true;
		$canVote = true;
	}
	// If they are logged in, then they can vote only
	else if (isset($_SESSION['userid'])) {
		$canVote = true;
	}

	if ($success) {
		$query = "SELECT city, state FROM lit_cities WHERE city_id = '$cityid'";
		$result = $conn->query($query);
		if ($result->num_rows != 1)
			$success = false;
		else {
			$data = $result->fetch_assoc();
			$city = $data['city'];
			$state = $data['state'];
		}
	}

	if (!$success) {
		header("Location: index.php");
	}

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

			<?php

				if ($success) {
					echo "<h2>" . $city . ", ". $state;
				}

			?>

			<div class="row" id="sort">		<!-- Begin sort div -->
				<span id="inline-sort">Sort by: </span>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    Newest
				    <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				    <li><a href="#">Newest</a></li>
				    <li><a href="#">Upcoming events</a></li>
				    <li><a href="#">Top</a></li>
				  </ul>
				</div>
			</div>			<!-- End sort div -->

			<div class="row well" id="contentid">

			<?php

				if ($success) {
					$query = "SELECT * FROM lit_post WHERE city_id = '$cityid'";
					if ($sort = 'newest') {
						$query .= " ORDER BY datetime_posted DESC";
					}
					else if ($sort = 'upcoming event') {
						$query .= " AND date_event >= CURDATE() AND endtime_event > CURTIME()";
						$query .= " ORDER BY date_event ASC, starttime_event ASC";

					}
					else if ($sort = 'top') {
						$query .= " ORDER BY upvotes DESC, downvotes ASC";
					}
					else if ($sort = 'hot') {
						// TODO: Need to implement a 'hot' algorithm
					}
					$result = $conn->query($query);
					while ($row = $result->fetch_assoc()) {
						// Print out each post
					}
				}

			?>

				<!-- POST-->
				<div class="row panel panel-default post">
					<div class="col-md-4">		<!-- Left side -->
						<div class="row date left-post">
							[date]
						</div>
						<div class="row time">
							[time]
						</div>
						<div class="row place">
							@ [place]
						</div>
					</div>
					<div class="col-md-8 right-post">		<!-- Right side -->
						<div class="row title">
							[title]
						</div>
						<div class="row description">
							[description here]
						</div>
						<div class="row host">
							posted by: [name]
						</div>
					</div>
					<div class="row interest button-toolbar">
						<button type="button" class="btn btn-danger btn-lg"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>  I'm interested (+1)</button>
						<button type="button" class="btn btn-default btn-lg"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>  2Lame (-0)</button>
					</div>
				</div>
				<!-- END POST-->

			</div>
		</div>								<!-- End main content column-->
		<div class="col-md-4" id="sidebarid"> <!-- The sidebar -->
			<div class="panel panel-default">
				<div class="panel-heading" id="sidebar-header">Sidebar  <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span></div>
				<div class="panel-body">
					<p>Welcome to the sidebar of my website.</p>
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