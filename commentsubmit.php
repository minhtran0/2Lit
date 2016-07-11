<?php

	include_once "global.php";

	if (isset($_SESSION['userid'])) {
		if (isset($_POST['submit'])) {
			$success = true;
			$comment = trim($_POST['comment']);
			$_SESSION['comment'] = $comment;
			$postid = $_SESSION['postid'];
			unset($_SESSION['postid']);
			$userid = $_SESSION['userid'];

			if (!is_numeric($postid) || $postid < 0 || $postid != round($postid, 0)) {
				$success = false;
				header("Location: index.php");
			}
			if (strlen(trim($comment)) == 0) {
				$success = false;
				header("Location: comments.php?post=$postid");
			}

			if ($success) {
				if ($stmt = $conn->prepare("INSERT INTO 
												lit_comment (post_id, user_id, datetime_comment, comment, upvotes, downvotes)
											VALUES
												(?, '$userid', NOW(), ?, '0', '0')")) {
					$stmt->bind_param('ss', $postid, $comment);
					$stmt->execute();
					$commentid = $stmt->insert_id;
					$stmt->close();
				}
				unset($_SESSION['comment']);

				// Change the comment number in post
				$query = "UPDATE lit_post SET comments = comments+1 WHERE post_id = '$postid'";
				$conn->query($query);

				// Send notification to users involved in comments and OP
				$query = "SELECT user_id FROM lit_comment WHERE post_id = '$postid'";
				$query_temp = "SELECT user_id FROM lit_post WHERE post_id = '$postid'";
				$result = $conn->query($query_temp);
				$data = $result->fetch_assoc();
				$op_userid = $data['user_id'];

				$result = $conn->query($query);
				$op_posted = false;
				$people = array();
				while($row = $result->fetch_assoc()) {
					$query2 = "UPDATE lit_user SET num_notifications = num_notifications+1 WHERE user_id = '".$row['user_id']."'";
					$query3 = "INSERT INTO lit_notification (user_id, commenter_user_id, post_id, datetime_posted) VALUES ('".$row['user_id']."', '".$_SESSION['userid']."', '$postid', NOW())";
					if ($_SESSION['userid'] != $row['user_id'] && !in_array($row['user_id'], $people)) {
						$conn->query($query3);
						$conn->query($query2);
					}
					if ($op_userid == $row['user_id'])
						$op_posted = true;
					$people[] = $row['user_id'];
				}
				// If op hasn't posted then we need to send him notification too
				if (!$op_posted) {
					$query2 = "UPDATE lit_user SET num_notifications = num_notifications+1 WHERE user_id = '$op_userid'";
					$query3 = "INSERT INTO lit_notification (user_id, commenter_user_id, post_id, datetime_posted) VALUES ('$op_userid', '".$_SESSION['userid']."', '$postid', NOW())";
					if ($_SESSION['userid'] != $row['user_id']) {
						$conn->query($query3);
						$conn->query($query2);
					}
				}

				header("Location: comments.php?post=$postid");
			}
		}
	}

?>