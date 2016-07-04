<?php

	include_once "global.php";

	if (!isset($_SESSION['userid'])) {
		header("Location: signin.php");	
	}

	$canPost = false;
	$canVote = false;

	$success = true;
	if (isset($_GET['city']))
		$cityid = $_GET['city'];
	// Else default to user's city
	else if (isset($_SESSION['userid'])) {
		$query = "SELECT city_id FROM lit_user WHERE user_id = '".$_SESSION['userid']."'";
		$result = $conn->query($query);
		$data = $result->fetch_assoc();
		$cityid = $data['city_id'];
		header("Location: view.php?city=".$cityid."&sort=hot");
	}

	if (!is_numeric($cityid) || $cityid < 0 || $cityid != round($cityid, 0)) {
		$success = false;
		header("Location: index.php");
	}
	if (isset($_GET['sort'])) {
		$sort = strtolower($_GET['sort']);
		if ($sort == 'hot' || $sort == 'new' || $sort == 'top' || $sort == 'upcoming')
			$sort = strtolower($_GET['sort']);
		else
			$sort = 'hot';
	}
	else
		$sort = 'hot';

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
          <?php

          	if (isset($_SESSION['userid'])) {
echo "          <ul class=\"nav navbar-nav\">\n";
echo "            <li"; if ($sort == 'hot') echo " class=\"active\""; echo "><a href=\"view.php?city=".$cityid."&sort=hot\">Hot</a></li>\n";
echo "            <li"; if ($sort == 'new') echo " class=\"active\""; echo "><a href=\"view.php?city=".$cityid."&sort=new\">New</a></li>\n";
echo "            <li"; if ($sort == 'upcoming') echo " class=\"active\""; echo "><a href=\"view.php?city=".$cityid."&sort=upcoming\">Upcoming</a></li>\n";
echo "            <li"; if ($sort == 'top') echo " class=\"active\""; echo "><a href=\"view.php?city=".$cityid."&sort=top\">Top</a></li>\n";
echo "            <li><a><strong>"; echo $city.", ".$state; echo "</strong></a></li>\n";
echo "          </ul>\n";
echo "          <form class=\"navbar-form navbar-left\" role=\"search\">\n";
echo "        <div class=\"form-group\">\n";
echo "          <input type=\"text\" class=\"form-control\" placeholder=\"Take a peek at other cities\">\n";
echo "        </div>\n";
echo "        <button type=\"submit\" class=\"btn btn-default\">Search</button>\n";
echo "      </form>";

			}

		?>

          <ul class="nav navbar-nav navbar-right">
            <?php

            	if (isset($_SESSION['userid'])) {
echo "			<li><a href=\"submit.php\">Submit a post</a></li>\n";
echo "            <li class=\"dropdown\">\n";
echo "              <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">". $_SESSION['username'] ." <span class=\"caret\"></span></a>\n";
echo "              <ul class=\"dropdown-menu\">\n";
echo "                <li><a href=\"#\">Profile</a></li>\n";
echo "                <li><a href=\"#\">Settings</a></li>\n";
echo "                <li><a href=\"#\">Privacy Policy</a></li>\n";
echo "                <li role=\"separator\" class=\"divider\"></li>\n";
echo "                <li><a href=\"logout.php\">Sign out</a></li>\n";
echo "              </ul>\n";
echo "            </li>";
				}
				else {
echo "			<li><a href=\"signin.php\">Sign in</a></li>\n";
				}

			?>

          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
	<div class="container">

	<br><br><br>

		<div class="col-md-8" id="contentid"> <!-- The main content column -->

			<div class="row" id="sort">		<!-- Begin sort div -->
				<span id="inline-sort">Sort by: </span>
				<div class="dropdown">
				  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				    <?php
				    	if ($success) {
				    		if ($sort == 'hot')	
				    			echo 'Hot';
				    		else if ($sort == 'new')
				    			echo 'Newest';
				    		else if ($sort == 'top')
				    			echo 'Top';
				    		else if ($sort == 'upcoming')	
				    			echo 'Upcoming events';
				    	}
				    ?>
				    <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
				    <li><a href="<?php if($success) echo "view.php?city=".$cityid."&sort=new";?>">Newest</a></li>
				    <li><a href="<?php if($success) echo "view.php?city=".$cityid."&sort=upcoming";?>">Upcoming events</a></li>
				    <li><a href="<?php if($success) echo "view.php?city=".$cityid."&sort=top";?>">Top</a></li>
				    <li><a href="<?php if($success) echo "view.php?city=".$cityid."&sort=hot";?>">Hot</a></li>
				  </ul>
				</div>
			</div>			<!-- End sort div -->

			<div class="row well" id="contentid">

			<?php

				if ($success) {
					$query = "SELECT * FROM lit_post WHERE city_id = '$cityid'";
					if ($sort = 'new') {
						$query .= " ORDER BY datetime_posted DESC";
					}
					else if ($sort = 'upcoming') {
						$query .= " AND date_event >= CURDATE() AND endtime_event > CURTIME()";
						$query .= " ORDER BY date_event ASC, starttime_event ASC";

					}
					else if ($sort = 'top') {
						$query .= " ORDER BY upvotes DESC, downvotes ASC";
					}
					else if ($sort = 'hot') {
						// TODO: Need to implement a 'hot' algorithm
					}
					//$result = $conn->query($query);
					//while ($row = $result->fetch_assoc()) {
						// Print out each post
					//}
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
						<button type="button" class="btn btn-default btn-lg upvote"><span class="glyphicon glyphicon-fire" aria-hidden="true"></span>  I'm interested (<span class="upvotes">1</span>)</button>
						<button type="button" class="btn btn-default btn-lg downvote"><span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>  2Lame (<span class="downvotes">0</span>)</button>
					</div>
				</div>
				<!-- END POST-->

			</div>
		</div>								<!-- End main content column-->
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>