$(document).ready(function() {
	$('.post').hover(function() { 
		$(this).toggleClass('hover'); 
	});
	$('.register').click(function() {
		location.href = "register.php";
	});
	$('.upvote').click(function(event) {
		$(this).toggleClass('btn-danger');
		var postid = $(this).parent().attr('post-id');

		var downvotes = $(this).siblings('.downvote');
		if (downvotes.hasClass('btn-primary')) {
			downvotes.children('.downvotes').html(parseInt(downvotes.children('.downvotes').html()) - 1);
			$(this).siblings('.downvote').removeClass('btn-primary');
			ajaxCall(postid, 0, -1);
		}
		if ($(this).hasClass('btn-danger')) {
			$(this).children('.upvotes').html(parseInt($(this).children('.upvotes').html()) + 1);
			ajaxCall(postid, 1, 0);
		}
		else {
			$(this).children('.upvotes').html(parseInt($(this).children('.upvotes').html()) - 1);
			ajaxCall(postid, -1, 0);
		}
	});
	$('.downvote').click(function(event) {
		$(this).toggleClass('btn-primary');
		var postid = $(this).parent().attr('post-id');

		var upvotes = $(this).siblings('.upvote');
		if (upvotes.hasClass('btn-danger')) {
			upvotes.children('.upvotes').html(parseInt(upvotes.children('.upvotes').html()) - 1);
			$(this).siblings('.upvote').removeClass('btn-danger');
			ajaxCall(postid, -1, 0);
		}
		if ($(this).hasClass('btn-primary')) {
			$(this).children('.downvotes').html(parseInt($(this).children('.downvotes').html()) + 1);
			ajaxCall(postid, 0, 1);
		}
		else {
			$(this).children('.downvotes').html(parseInt($(this).children('.downvotes').html()) - 1);
			ajaxCall(postid, 0, -1);
		}
	});
	function ajaxCall(postid, upvote, downvote) {
		$.ajax({
			url: 'upvote.php',
			type: 'POST',
			data: {'postid': postid,
					'upvote': upvote,
					'downvote': downvote},
			success: function(result){
             },
			error: function(xhr, desc, err) {
			    console.log(xhr);
			    console.log("Details: " + desc + "\nError:" + err);
			}
		});
	}
});