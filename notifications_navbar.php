<?php

	include_once "global.php";

	$success = true;
	if (!isset($_SESSION['userid'])) {
		$success = false;
		header("Location: signin.php");
	}


	if ($success) {
		$userid = $_SESSION['userid'];
		$query = "SELECT
					*
				FROM 
					lit_notification
				WHERE
					user_id = '$userid'
				ORDER BY
					datetime_posted DESC 
				LIMIT 5";
		$result = $conn->query($query);
		$cityid = $_SESSION['cityid'];

		$notifications = array();
		while ($row = $result->fetch_assoc()) {
			$query3 = "SELECT title FROM lit_post WHERE post_id = '".$row['post_id']."'";
			$result3 = $conn->query($query3);
			$data3 = $result3->fetch_assoc();

			if (intval($row['commenter_user_id']) >= 0) {
				$query2 = "SELECT username FROM lit_user WHERE user_id = '".$row['commenter_user_id']."'";
				$result2 = $conn->query($query2);
				$data2 = $result2->fetch_assoc();
				$noti = array(
					"append" => "<li><a href=\"comments.php?post=".$row['post_id']."\" class=\"cName\"><strong>".$data2['username']."</strong> has <strong>commented</strong> on the post<br> <span class=\"cPost\">\"".$data3['title']."\"</span></a></li>"
				);
			}
			else {
				$noti = array(
					"append" => "<li><a href=\"comments.php?post=".$row['post_id']."\" class=\"cName\">You have <strong>".(abs($row['commenter_user_id'])*10)." upvotes</strong> on the post<br> <span class=\"cPost\">\"".$data3['title']."\"</span></a></li>"
				);
			}
			$notifications[] = $noti;
		}

		$query = "UPDATE lit_user SET num_notifications = 0 WHERE user_id = '".$_SESSION['userid']."'";
		$conn->query($query);

		echo json_encode($notifications);
	}

?>

