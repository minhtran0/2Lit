<?php

	include_once "global.php";

	if (isset($_SESSION['userid'])) {
		if(isset($_POST['postid'])) {
			$postid = $_POST['postid'];
			$upvote = $_POST['upvote'];
			$downvote = $_POST['downvote'];
			$userid = $_SESSION['userid'];
			$query = "SELECT upvotes, downvotes FROM lit_post WHERE post_id = '$postid'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			$tot_upvotes = intval($upvote) + intval($data['upvotes']);
			$tot_downvotes = intval($downvote) + intval($data['downvotes']);
			$query = "UPDATE lit_post SET upvotes = '$tot_upvotes', downvotes = '$tot_downvotes' WHERE post_id = '$postid'";
			$conn->query($query);
			$query = "SELECT response_id, response FROM lit_response WHERE user_id = '$userid' AND post_id = '$postid'";
			$result = $conn->query($query);
			if ($result->num_rows == 0) {
				$number = 0;
				if ($upvote == "1")	$number = 1;
				else if ($downvote == "1")	$number = 2;
				$query = "INSERT INTO lit_response (user_id, post_id, response) VALUES ('$userid', '$postid', '$number')";
				$conn->query($query);
			}
			else {
				$data = $result->fetch_assoc();
				$response_id = $data['response_id'];
				$number = 0;
				if ($upvote == "1")	$number = 1;
				else if ($downvote == "1")	$number = 2;
				$query = "UPDATE lit_response SET response = '$number' WHERE response_id = '$response_id' AND user_id = '$userid'";
				$conn->query($query);
			}
			// Update OP's upvote and downvote scores
			$query = "SELECT user_id FROM lit_post WHERE post_id = '$postid'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			$op_userid = $data['user_id'];
			$query = "SELECT total_upvotes, total_downvotes FROM lit_user WHERE user_id = '$op_userid'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			$tot_upvotes = intval($upvote) + intval($data['total_upvotes']);
			$tot_downvotes = intval($downvote) + intval($data['total_downvotes']);
			$query = "UPDATE lit_user SET total_upvotes = '$tot_upvotes', total_downvotes = '$tot_downvotes' WHERE user_id = '$op_userid'";
			$conn->query($query);

			// Send notification if upvote is multiple of 10
			$query = "SELECT upvotes FROM lit_post WHERE post_id = '$postid'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			if ($data['upvotes'] % 10 == 0 && $data['upvotes'] > 0) {
				$num = -1 * $data['upvotes']/10;
				$query = "INSERT INTO 
							lit_notification (user_id, post_id, datetime_posted, commenter_user_id)
						VALUES
							('$op_userid', '$postid', NOW(), '$num')";
				$conn->query($query);
				$query2 = "UPDATE lit_user SET num_notifications = num_notifications+1 WHERE user_id = '$op_userid'";
				$conn->query($query2);
			}

			unset($_POST['postid']);
		}
	}

?>