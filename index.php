<?php

	include_once "global.php";

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
          <a class="navbar-brand lit-heading" href="index.php">2Lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <?php

          	if (isset($_SESSION['userid'])) {
echo "          <ul class=\"nav navbar-nav\">\n";
echo "            <li><a><strong>Global</strong></a></li>\n";
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
			<?php

				if (!isset($_SESSION['userid'])) {
echo "			<div class=\"row jumbotron\">\n";
echo "				<h1>Wanna know what's cool in your area?</h1>\n";
echo "				<p>Click below to sign up for an account! Look below to see some of the hottest events from around the world!</p>\n";
echo "				<button type=\"button\" class=\"btn btn-primary btn-lg register\">Sign me up!</button>\n";
echo "			</div>";
				}

			?>

			<div class="row well" id="contentid">

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
							posted by: <a href="#">[name]</a>
						</div>
						<div class="row host">
							<a href="#">(0 comments)</a>
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