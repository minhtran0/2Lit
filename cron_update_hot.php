<?php

	include_once "global.php";

	$query = 
		"SELECT 
		    post_id, upvotes, downvotes, datetime_posted
		FROM 
		    lit_post
		ORDER BY 
		    LOG10(ABS(upvotes - downvotes) + 1) * SIGN(upvotes - downvotes)
		    + (UNIX_TIMESTAMP(datetime_posted) / 45000) DESC
		LIMIT 1000";

?>