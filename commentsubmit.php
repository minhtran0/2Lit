<?php

	include_once "global.php";

	if (isset($_SESSION['userid'])) {
		if (isset($_POST['submit'])) {
			$comment = trim($_POST['comment']);
			$_SESSION['comment'] = $comment;
			$postid = $_GET['post'];
			$userid = $_SESSION['userid'];

			if (!is_numeric($postid) || $postid < 0 || $postid != round($postid, 0)) {
				$success = false;
				header("Location: index.php");
			}

			if ($success) {
				if ($stmt = $conn->prepare("INSERT INTO 
												lit_comment (post_id, user_id, datetime_comment, comment, upvotes, downvotes)
											VALUES
												(?, $userid, NOW(), ?, '0', '0')")) {
					$stmt->bind_param('ss', $postid, $comment);
					$stmt->execute();
					$stmt->close();
				}

				// Change the comment number in post
				$query = "SELECT comments FROM lit_post WHERE post_id = '$postid'";
				$result = $conn->query($query);
				$data = $result->fetch_assoc();
				$num_comments = intval($data['comments']) + 1;
				$query = "UPDATE lit_post SET comments = '$num_comments'";
				$conn->query($query);

				header("Location: comments.php?post='$postid'");
			}
		}
	}

?>