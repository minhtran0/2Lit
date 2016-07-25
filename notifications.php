<?php

include_once "global.php";

$success = true;
if (!isset($_SESSION['userid'])) {
	$success = false;
	header("Location: signin.php");
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
	$cityid = $_SESSION['cityid'];
}

?>

<html>
<head>
	<title>too lit</title>
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.min.css"></link>
	<link rel="stylesheet" href="css/style.css"></link>
	<link href='//fonts.googleapis.com/css?family=Chivo' rel='stylesheet' type='text/css'>
	<link href='//fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
	
</style>
</head>
<body>
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top navbar-font">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand lit-heading" href=" <?php echo "view.php?city=".$_SESSION['cityid']."&sort=hot";?> ">too lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></a>
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
						$query = "SELECT num_notifications FROM lit_user WHERE user_id = '".$_SESSION['userid']."'";
						$result = $conn->query($query);
						$data = $result->fetch_assoc();
						$num = $data['num_notifications'];
						echo "			<li><a href=\"submit.php\">Submit a post</a></li>\n";
						echo "			<li class=\"dropdown\">\n"; 
						echo "              <a class=\"dropdown-toggle noti\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\"><span class=\"glyphicon glyphicon-bell\"></span>"; if ($num>0) echo "<span class=\"badge notification\">".$num."</span>"; echo "<span class=\"caret\"></span></a>\n"; 
						echo "              <ul class=\"dropdown-menu notification-list\">\n"; 
						echo "					<li class=\"dropdown-header\">Notifications</li>";
						echo "              </ul>\n"; 
						echo "            </li>\n";
						echo "            <li class=\"dropdown\">\n";
						echo "              <a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">". $_SESSION['username'] ." <span class=\"caret\"></span></a>\n";
						echo "              <ul class=\"dropdown-menu\">\n";
						echo "                <li><a href=\"#\">Profile</a></li>\n";
						echo "                <li><a href=\"#\">Settings</a></li>\n";
						echo "                <li role=\"separator\" class=\"divider\"></li>\n";
						echo "                <li><a href=\"files/termsandconditions.html\">Terms and Conditions</a></li>\n";
						echo "                <li><a href=\"files/privacypolicy.html\">Privacy Policy</a></li>\n";
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
		<div class="row">
			<h2 class="subheading">Your notifications</h2>
		</div>
		<div class="row">
			<div class="col-md-2"></div>
			<div class="col-md-7" id="contentid"> <!-- The main content column -->
				<div class="row">
					
					<?php
					if ($success) {
						$userid = $_SESSION['userid'];
						$query = "SELECT
						*
						FROM 
						lit_notification
						WHERE
						user_id = '$userid'
						ORDER BY
						datetime_posted DESC";
						$result = $conn->query($query);
						while ($row = $result->fetch_assoc()) {
							if ($row['commenter_user_id'] >= 0) {
								$query2 = "SELECT username FROM lit_user WHERE user_id = '".$row['commenter_user_id']."'";
								$result2 = $conn->query($query2);
								$data2 = $result2->fetch_assoc();
								$commenter_name = $data2['username'];
								$query3 = "SELECT title FROM lit_post WHERE post_id = '".$row['post_id']."'";
								$result3 = $conn->query($query3);
								$data3 = $result3->fetch_assoc();
								$title = $data3['title'];

								echo "				<div class=\"row panel panel-default notification-container\">\n"; 
								echo "					<div class=\"col-md-12 col-sm-12 col-xs-12 notification-box\">\n"; 
								echo "						<div class=\"row comment\"><a href=\""; echo "comments.php?post=".$row['post_id']; echo "\" class=\"cName\"><strong>"."$commenter_name"; echo "</strong> has <strong>commented</strong> on the post <span class=\"cPost\">\""; echo $title; echo "\"</span></a></div>\n"; 
								echo "						<div class=\"row comment-time\">".time_elapsed_string($row['datetime_posted'])."</div>\n"; 
								echo "					</div>\n"; 
								echo "		 		</div>\n";
							}
							else {
								$query3 = "SELECT title FROM lit_post WHERE post_id = '".$row['post_id']."'";
								$result3 = $conn->query($query3);
								$data3 = $result3->fetch_assoc();
								$title = $data3['title'];
								echo "				<div class=\"row panel panel-default notification-container\">\n"; 
								echo "					<div class=\"col-md-12 col-sm-12 col-xs-12 notification-box\">\n"; 
								echo "						<div class=\"row comment\"><a href=\""; echo "comments.php?post=".$row['post_id']; echo "\" class=\"cName\">You have <strong>"; echo (abs($row['commenter_user_id'])*5)." upvotes</strong> on the post <span class=\"cPost\">\""; echo $title; echo "\"</span></a></div>\n"; 
								echo "						<div class=\"row comment-time\">".time_elapsed_string($row['datetime_posted'])."</div>\n"; 
								echo "					</div>\n"; 
								echo "		 		</div>\n";
							}
						}
					}

					function time_elapsed_string($datetime, $full = false) {
						$now = new DateTime;
						$ago = new DateTime($datetime);
						$diff = $now->diff($ago);

						$diff->w = floor($diff->d / 7);
						$diff->d -= $diff->w * 7;

						$string = array(
							'y' => 'year',
							'm' => 'month',
							'w' => 'week',
							'd' => 'day',
							'h' => 'hr',
							'i' => 'min',
							's' => 'sec',
							);
						foreach ($string as $k => &$v) {
							if ($diff->$k) {
								$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
							} else {
								unset($string[$k]);
							}
						}

						if (!$full) $string = array_slice($string, 0, 1);
						return $string ? implode(', ', $string) . ' ago' : 'just now';
					}
					?>

				</div>


			</div>
		</div>								<!-- End main content column-->
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>