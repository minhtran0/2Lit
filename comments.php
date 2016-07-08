<?php

	include_once "global.php";

	if (!isset($_SESSION['userid'])) {
		header("Location: signin.php");	
	}

	$canPost = false;
	$canVote = false;

	$success = true;

	if (isset($_GET['post']))
		$postid = $_GET['post'];
	// Else default to user's city
	else if (isset($_SESSION['userid'])) {
		$query = "SELECT city_id FROM lit_user WHERE user_id = '".$_SESSION['userid']."'";
		$result = $conn->query($query);
		$data = $result->fetch_assoc();
		$cityid = $data['city_id'];
		header("Location: view.php?city=".$cityid."&sort=hot");
	}
	if (!is_numeric($postid) || $postid < 0 || $postid != round($postid, 0)) {
		$success = false;
		header("Location: index.php");
	}

	if ($success) {
		$query = "SELECT city_id FROM lit_post WHERE post_id = '$postid'";
		$result = $conn->query($query);
		$data = $result->fetch_assoc();
		$cityid = $data['city_id'];
	}

	// If user is logged in and is in the same city -- then they can post and vote
	if (isset($_SESSION['userid']) && $_SESSION['cityid'] == $cityid) {
		$canPost = true;
		$canVote = true;
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
    <div class="container-fluid">
    	<br><br><br>
		<div class="row">
			<img src="header.png" class="img-responsive">
		</div>
    </div>
	<div class="container">

		<div class="row">
		<div class="col-md-7" id="contentid"> <!-- The main content column -->

			<div class="row well" id="contentid">

			<!-- POST-->

			<?php

				if ($success) {
					$query = "SELECT * FROM lit_post WHERE post_id = '$postid'";
					$result = $conn->query($query);
					$op_userid = "";

					// There's only one row, but I'm too lazy to take the while loop out and reformat.
					while ($row = $result->fetch_assoc()) {
						// Print out each post
						$query = "SELECT username, total_upvotes, total_downvotes from lit_user WHERE user_id = '".$row['user_id']."'";
						$result2 = $conn->query($query);
						$data = $result2->fetch_assoc();
						$username = $data['username'];
						$op_upvotes = $data['total_upvotes'];
						$op_downvotes = $data['total_downvotes'];
						$upvotes = $row['upvotes'];
						$downvotes = $row['downvotes'];
						$postid = $row['post_id'];
						$op_userid = $row['user_id'];

						$query = "SELECT response FROM lit_response WHERE user_id = '".$_SESSION['userid']."' AND post_id = '$postid'";
						$result3 = $conn->query($query);
						if ($result3->num_rows == 0) {
							$response = 0;
						}
						else {
							$data2 = $result3->fetch_assoc();
							if ($data2['response'] == "1")
								$response = 1;
							else if ($data2['response'] == "2")
								$response = 2;
							else
								$response = 0;
						}



echo "				<div class=\"row panel panel-default post\">\n";
echo "					<div class=\"row\">";
echo "					<div class=\"col-md-4\">		<!-- Left side -->\n";
echo "						<div class=\"row date left-post\">\n";
echo "							".date("m-d-Y", strtotime($row['date_event']))."\n";
echo "						</div>\n";
echo "						<div class=\"row time\">\n";
echo "							".date('g:i a', strtotime($row['starttime_event']))."-\n<br>".date('g:i a', strtotime($row['endtime_event']));
echo "						</div>\n";
echo "						<div class=\"row place\">\n";
echo "							@ ".$row['location']."\n";
echo "						</div>\n";
echo "					</div>\n";
echo "					<div class=\"col-md-8 right-post\">		<!-- Right side -->\n";
echo "						<div class=\"row title\">\n";
echo "							".$row['title']."\n";
echo "						</div>\n";
echo "						<div class=\"row description\">\n";
echo "							".$row['description']."\n";
echo "						</div>\n";
echo "						<div class=\"row host\">\n";
echo "							posted ".time_elapsed_string($row['datetime_posted'])." by: <a href=\"#\">".$username."</a> (+".$op_upvotes.", -".$op_downvotes.")\n";
echo "						</div>\n";
echo "						<div class=\"row host\">\n";
echo "							<a href=\"#\">(0 comments)</a>\n";
echo "						</div>";
echo "					</div>\n";
echo "					</div>\n";
echo "					<div class=\"row interest button-toolbar\" post-id=\"$postid\">\n";
echo "						<button type=\"button\" class=\"btn "; if ($response==1) echo "btn-danger"; else echo "btn-default"; echo " upvote\"><span class=\"glyphicon glyphicon-fire\" aria-hidden=\"true\"></span>  I'm interested (<span class=\"upvotes\">".$upvotes."</span>)</button>\n";
echo "						<button type=\"button\" class=\"btn "; if ($response==2) echo "btn-primary"; else echo "btn-default"; echo " downvote\"><span class=\"glyphicon glyphicon-thumbs-down\" aria-hidden=\"true\"></span>  2Lame (<span class=\"downvotes\">".$downvotes."</span>)</button>\n";
echo "					</div>\n";
echo "				</div>\n\n";

					}

echo "				<!-- Send start -->\n"; 
echo "				<div class=\"row panel panel-default comment-container send-style\">\n"; 
echo "					<div class=\"row\">\n"; 
echo "					<form class=\"form-inline\" method=\"post\" action=\"commentsubmit.php\">\n"; 
echo "					  <div class=\"form-group\">\n"; 
echo "				    <textarea cols=\"50\" textmax=\"200\" class=\"form-control\" id=\"comment\" name=\"comment\" placeholder=\"What's on your mind?\">"; if (isset($_SESSION['comment'])) {echo $_SESSION['comment']; unset($_SESSION['comment']);} echo "</textarea>\n"; 
echo "				  </div>\n";
echo "					  </div>\n"; 
echo "					  <button type=\"submit\" class=\"btn btn-default \" name=\"submit\">Send</button>\n"; 
echo "					</form>\n"; 
echo "					</div>\n"; 
echo "					<div class=\"row\">\n"; 
echo "						<div class=\"col-md-8 col-xs-8 col-sm-8\"></div>\n"; 
echo "						<div id=\"comment-feedback\"></div>\n"; 
echo "					</div>\n"; 
echo "					</div>\n";
echo "				<!-- Send end-->\n"; 

					$query = "SELECT * FROM lit_comment WHERE post_id = '$postid' ORDER BY comment_id ASC";
					$result = $conn->query($query);

					while ($row = $result->fetch_assoc()) {

						$query2 = "SELECT response FROM lit_comment_response WHERE user_id = '".$_SESSION['userid']."' AND post_id = '$postid'";
						$result2 = $conn->query($query);
						if ($result2->num_rows == 0) {
							$response = 0;
						}
						else {
							$data2 = $result2->fetch_assoc();
							if ($data2['response'] == "1")
								$response = 1;
							else if ($data2['response'] == "2")
								$response = 2;
							else
								$response = 0;
						}

						$votes = intval($row['upvotes']) - intval($row['downvotes']);
						$userid = $row['user_id'];
						$comment = $row['comment'];
						$datetime_comment = $row['datetime_comment'];
						$commentid = $row['comment_id'];

						$query3 = "SELECT username FROM lit_user WHERE user_id = '$userid'";
						$result3 = $conn->query($query3);
						$data3 = $result3->fetch_assoc();
						$username = $data3['username'];

echo "\n"; 
echo "				<div class=\"row panel panel-default comment-container\">\n"; 
echo "					<div class=\"col-md-1 left-comment-pane\" comment-id=\"$commentid\">		<!-- Left pane -->\n"; 
echo "						<div class=\"row\"><span class=\"glyphicon glyphicon-chevron-up cUpvote"; if ($response==1) echo " green"; echo "\" aria-hidden=\"true\"></span></div>\n"; 
echo "						<div class=\"row\"><span class=\"score\">"; echo "$votes"; echo "</span></div>\n"; 
echo "						<div class=\"row\"><span class=\"glyphicon glyphicon-chevron-down cDownvote"; if ($response==2) echo " red"; echo "\" aria-hidden=\"true\"></span></div>\n"; 
echo "					</div>\n"; 
echo "					<div class=\"col-md-10 right-comment-pane\">		<!-- Right pane -->\n"; 
echo "						<div class=\"row comment-name\"><strong>"; if ($op_userid==$userid) echo "<mark>$username - OP</mark>"; else echo "$username"; echo "</strong></div>\n"; 
echo "						<div class=\"row comment\">"; echo "$comment"; echo "</div>\n"; 
echo "						<div class=\"row comment-time\">"; echo time_elapsed_string($datetime_comment); echo "</div>\n"; 
echo "					</div>\n"; 
echo "				</div>\n"; 
echo "\n"; 
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

				<!-- END COMMENTS-->

			</div>
		</div>								<!-- End main content column-->
		</div>
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
</body>
</html>