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
				"append" => "<li><a href=\"comments.php?post=".$row['post_id']."\" class=\"cName\"><strong>".$data2['username']."</strong> has <strong>commented</strong> on the post<br> <span class=\"cPost\">\"".$data3['title']."\"</span>\n<span class=\"row comment-time\">".time_elapsed_string($row['datetime_posted'])."</span></a>\n</li>"
				);
		}
		else {
			$noti = array(
				"append" => "<li><a href=\"comments.php?post=".$row['post_id']."\" class=\"cName\">You have <strong>".(abs($row['commenter_user_id'])*5)." upvotes</strong> on the post<br> <span class=\"cPost\">\"".$data3['title']."\"</span>\n<span class=\"row comment-time\">".time_elapsed_string($row['datetime_posted'])."</span></a>\n</li>"
				);
		}
		$notifications[] = $noti;
	}

	$query = "UPDATE lit_user SET num_notifications = 0 WHERE user_id = '".$_SESSION['userid']."'";
	$conn->query($query);

	echo json_encode($notifications);
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

