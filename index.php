<?php

	include_once "global.php";

	if (isset($_SESSION['userid'])) {
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
	<link href='http://fonts.googleapis.com/css?family=Overlock' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Chivo' rel='stylesheet' type='text/css'>
	<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>

		
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
          <a class="navbar-brand lit-heading" href="index.php">too lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></a>
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

    <div class="container-fluid">
		<a href="index.php"><h1 class="lit-heading" id="heading">2Lit  <span class="glyphicon glyphicon-fire" aria-hidden="true"></span></h1></a>
		<div class="row">
		<div class="col-md-1"></div>
			<div class="col-md-7">
				<div class="row jumbotron">
					<h1>Want to know what's cool in your area?</h1>
					<p>2lit quickly filters the hottest events in your city, town, or university.</p>
					<button type="button" class="btn btn-primary btn-lg register">Sign me up!</button>
				</div>
			</div>
			<div class="col-md-4"></div>
		</div>
	</div>

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<script type="text/javascript">
		var images = ['img-audience.jpg','img-munich.jpg'];
		$('body').css({'background-image': 'url(' + images[Math.floor(Math.random() * images.length)] + ')'});
	</script>
</body>
</html>