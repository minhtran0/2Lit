<?php

	include_once "global.php";

	$query = 
		"SELECT 
		    post_id
		FROM 
		    lit_post
		WHERE
			date_event >= CURDATE()
		ORDER BY 
		    LOG10(ABS(upvotes - downvotes) + 1) * SIGN(upvotes - downvotes)
		    + (UNIX_TIMESTAMP(datetime_posted) / 70000) DESC
		LIMIT 50";

	$counter = 1;
	$result = $conn->query($query);
	while ($row = $result->fetch_assoc() && $counter) {
		$query2 = "UPDATE lit_hot SET post_id = '".$row['post_id']."' WHERE hot_id = '$counter'";
		$conn->query($query2);
		$counter++;
	}

?>