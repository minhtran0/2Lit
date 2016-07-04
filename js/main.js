$(document).ready(function() {
	$('.post').hover(function() { 
		$(this).toggleClass('hover'); 
	});
	$('.upvote').bind('click', function(event) {
		$(this).toggleClass('btn-danger');
		if ($(this).hasClass('btn-danger')) {
			$(this).children('.upvotes').html(parseInt($(this).children('.upvotes').html()) + 1);
		}
		else {
			$(this).children('.upvotes').html(parseInt($(this).children('.upvotes').html()) - 1);
		}
		var downvotes = $(this).siblings('.downvote');
		if (downvotes.hasClass('btn-primary')) {
			downvotes.children('.downvotes').html(parseInt(downvotes.children('.downvotes').html()) - 1);
		}
		if ($(this).siblings('.downvote').hasClass('btn-primary')) {
			$(this).siblings('.downvote').removeClass('btn-primary')
		}
	});
	$('.downvote').bind('click', function(event) {
		$(this).toggleClass('btn-primary');
		if ($(this).hasClass('btn-primary')) {
			$(this).children('.downvotes').html(parseInt($(this).children('.downvotes').html()) + 1);
		}
		else {
			$(this).children('.downvotes').html(parseInt($(this).children('.downvotes').html()) - 1);
		}
		var upvotes = $(this).siblings('.upvote');
		if (upvotes.hasClass('btn-danger')) {
			upvotes.children('.upvotes').html(parseInt(upvotes.children('.upvotes').html()) - 1);
		}
		if ($(this).siblings('.upvote').hasClass('btn-danger')) {
			$(this).siblings('.upvote').removeClass('btn-danger')
		}
	});
	function ajaxCall(postid, addUp, addDown) {
		$.ajax({
			url: 'upvote.php',
			type: 'POST',
			data: {'postid': postid,
					'addUp': addUp,
					'addDown': addDown},
			error: function(xhr, desc, err) {
			    console.log(xhr);
			    console.log("Details: " + desc + "\nError:" + err);
			}
		});
	}
});