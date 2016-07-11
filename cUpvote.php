<?php

	include_once "global.php";

	if (isset($_SESSION['userid'])) {
		if(isset($_POST['commentid'])) {
			$commentid = $_POST['commentid'];
			$upvote = $_POST['cUpvote'];
			$downvote = $_POST['cDownvote'];
			$userid = $_SESSION['userid'];

			$query = "UPDATE 
						lit_comment 
					SET 
						upvotes = upvotes+intval($upvote), downvotes = downvotes+intval($downvote) 
					WHERE 
						comment_id = '$commentid'";
			$conn->query($query);

			$query = "SELECT 
						response_id, response 
					FROM 
						lit_comment_response 
					WHERE 
						user_id = '$userid' AND comment_id = '$commentid'";
			$result = $conn->query($query);
			if ($result->num_rows == 0) {
				$number = 0;
				if ($upvote == "1")	$number = 1;
				else if ($downvote == "1")	$number = 2;
				$query = "INSERT INTO 
							lit_comment_response (user_id, comment_id, response) 
						VALUES 
							('$userid', '$commentid', '$number')";
				$conn->query($query);
			}
			else {
				$data = $result->fetch_assoc();
				$response_id = $data['response_id'];
				$number = 0;
				if ($upvote == "1")	$number = 1;
				else if ($downvote == "1")	$number = 2;
				$query = "UPDATE 
							lit_comment_response 
						SET 
							response = '$number' 
						WHERE 
							response_id = '$response_id' AND user_id = '$userid'";
				$conn->query($query);
			}
			// Update OP's upvote and downvote scores

			$query = "SELECT post_id FROM lit_comment WHERE comment_id = '$commentid'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			$postid = $data['post_id'];

			$query = "SELECT user_id FROM lit_comment WHERE comment_id = '$commentid'";
			$result = $conn->query($query);
			$data = $result->fetch_assoc();
			$op_userid = $data['user_id'];

			$query = "UPDATE 
						lit_user 
					SET 
						total_upvotes = total_upvotes+intval($upvote), total_downvotes = total_downvotes+intval($downvote) 
					WHERE 
						user_id = '$op_userid'";
			$conn->query($query);

			unset($_POST['commentid']);
		}
	}

?>