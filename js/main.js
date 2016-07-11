$(document).ready(function() {
	var loading = false;
	$(window).scroll(function() {
	    if (!loading && ($(window).scrollTop() >  $(document).height() - $(window).height() - 100)) {
	        loading= true;

	        // your content loading call goes here.

	        loading = false; // reset value of loading once content loaded
	    }
	});
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
	$('.cUpvote').click(function(event) {
		$(this).toggleClass('green');
		var commentid = $(this).closest('.left-comment-pane').attr('comment-id');

		var cDownvotes = $(this).closest('.left-comment-pane').find('.cDownvote');
		if (cDownvotes.hasClass('red')) {
			cDownvotes.closest('.left-comment-pane').find('.score').html(parseInt(cDownvotes.closest('.left-comment-pane').find('.score').html()) + 1);
			$(this).closest('.left-comment-pane').find('.cDownvote').removeClass('red');
			ajaxCallComment(commentid, 0, -1);
		}
		if ($(this).hasClass('green')) {
			$(this).closest('.left-comment-pane').find('.score').html(parseInt($(this).closest('.left-comment-pane').find('.score').html()) + 1);
			ajaxCallComment(commentid, 1, 0);
		}
		else {
			$(this).closest('.left-comment-pane').find('.score').html(parseInt($(this).closest('.left-comment-pane').find('.score').html()) - 1);
			ajaxCallComment(commentid, -1, 0);
		}
	});
	$('.cDownvote').click(function(event) {
		$(this).toggleClass('red');
		var commentid = $(this).closest('.left-comment-pane').attr('comment-id');

		var cUpvotes = $(this).closest('.left-comment-pane').find('.cUpvote');
		if (cUpvotes.hasClass('green')) {
			cUpvotes.closest('.left-comment-pane').find('.score').html(parseInt(cUpvotes.closest('.left-comment-pane').find('.score').html()) - 1);
			$(this).closest('.left-comment-pane').find('.cUpvote').removeClass('green');
			ajaxCallComment(commentid, -1, 0);
		}
		if ($(this).hasClass('red')) {
			$(this).closest('.left-comment-pane').find('.score').html(parseInt($(this).closest('.left-comment-pane').find('.score').html()) - 1);
			ajaxCallComment(commentid, 0, 1);
		}
		else {
			$(this).closest('.left-comment-pane').find('.score').html(parseInt($(this).closest('.left-comment-pane').find('.score').html()) + 1);
			ajaxCallComment(commentid, 0, -1);
		}
	});
	function ajaxCallComment(commentid, cUpvote, cDownvote) {
		$.ajax({
			url: 'cUpvote.php',
			type: 'POST',
			data: {'commentid': commentid,
					'cUpvote': cUpvote,
					'cDownvote': cDownvote},
			success: function(result){
             },
			error: function(xhr, desc, err) {
			    console.log(xhr);
			    console.log("Details: " + desc + "\nError:" + err);
			}
		});
	}
	var text_max_comment = 200;
    $('#comment-feedback').text(text_max_comment);

    $('#comment').keyup(function() {
        var text_length = $('#comment').val().length;
        var text_remaining = text_max_comment - text_length;

        $('#comment-feedback').text(text_remaining);
    });
});