<?php

	include_once "global.php";

	if(isset($_SESSION['userid'])) {
		unset($_SESSION['userid']);
		unset($_SESSION['city']);
		unset($_SESSION['state']);
	}

	$conn->close();

?>