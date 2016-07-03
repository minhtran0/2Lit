<?php

	include_once "global.php";

	if(isset($_SESSION['userid'])) {
		unset($_SESSION['userid']);
		unset($_SESSION['cityid']);
		header("Location: index.php");
	}

	$conn->close();

?>