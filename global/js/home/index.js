var refreshTopics = true,
	refreshDelay = 3000,
	fGames = false,
	nMessages = ['', 'mentioned you in a post.', 'left a message on your profile.', 'sent you a friend request.'];

function load_new_topics() {
	if (refreshTopics) {
		$.get("/home/load_recent_topics", {'games': fGames}, function (msg) {
			$('#topic-list').html(msg.html);
			setTimeout(load_new_topics, refreshDelay);
		});
	} else {
		setTimeout(load_new_topics, refreshDelay);
	}
}

function refreshNotifications() {
	$.get("/notifications/get_notifications/", function (msg) {
		var nMsg = '',
			totalNew = 0;

		$.each(msg, function(x, y) {
			var readBtn = (this.is_read) ? '' : '<a href="#" class="btn16 btn n-read" title="mark as read"><span class="icon16 icon-read"></span></a>',
				isNew = (this.is_read) ? '' : '<span class="n-new">New!</span>';

			nMsg += '<li class="n-content" data-n-id="'+this.id+'">'
					+ '<div class="n-head"><div class="n-row"><a href="/user/'+this.username+'"><img title="'+this.username+'" src="/images/avatars/'+this.author_id+'_headshot.png" style="width: 100%"/></a></div></div>'
					+ '<div class="n-message"><div class="n-row"><a href="'+this.target+'"><b>'+this.username+'</b> '+nMessages[this.type]+'<div class="n-info">'+isNew + ' ' + this.time +'</div></a></div></div>'
					+ '<div class="n-buttons"><div class="btn-group">'
						+ readBtn + '<a href="#" class="btn16 btn n-delete" title="remove"><span class="icon16 icon-delete"></span></a>'
					+ '</div></div>'
					+ '<div class="clearfix"></div></li>';

			totalNew += (this.is_read) ? 0 : 1;
		});

		$('#notification-list').html(nMsg);

		if (totalNew > 0) $('#n-total').show().text(totalNew);
		else $('#n-total').hide();
	});
}

$(document).ready(function () {
	$('#dashboard-content div').eq(0).show();
	$('#dashboard-content > div').not(':eq(0)').hide();

	$('#dashboard-tabs li a').on('click', function () {
		$('#dashboard-tabs li').removeClass('active');
		$(this).parent().addClass('active');

		$('#dashboard-content > div').hide();
		$($(this).attr('href')).show();

		return false;
	});

	refreshTopics = $('#update-topics').is(':checked');
	$('#update-topics').change(function() { refreshTopics = $('#update-topics').is(':checked'); });

	fGames = $('#display_f-games').is(':checked');
	$('#display_f-games').change(function() { fGames = $('#display_f-games').is(':checked'); });

	load_new_topics();

	$('.n-link').click(function(e) {
		$.get("/home/mark_read/" + $(this).data('n-id'));
	});

	$('#n-refresh').click(refreshNotifications);

	$('#n-deleteall').click(function() {
		$('#notification-list .n-delete').each(function(index, delete_link){
			$(delete_link).trigger('click');
		});

		refreshNotifications();

		return false;
	});

	$('#n-readall').click(function() {
		$.get("/notifications/read_all/", function(msg) {
			if (msg.success) {
				refreshNotifications();
			}
		});
	});

	$('#notification-list').on('click', '.notification-primary-link', (function(e) {
		e.preventDefault();
		var $el = $(this);
		$.get("/notifications/set_read/", {'id': $el.closest('li').data('n-id')}, function (msg) {
			document.location = $el.attr('href');
		});
	}));

	$('#notification-list').on('click', '.n-read', (function(e) {
		var $el = $(this);
		$.get("/notifications/set_read/", {'id': $el.closest('li').data('n-id')}, function (msg) {
			if (msg.success) {
				refreshNotifications();
			}
		});
	}));

	$('#notification-list').on('click', '.n-delete', (function(e) {
		var $el = $(this);
		$.get("/notifications/dismiss/", {'id': $el.closest('li').data('n-id')}, function (msg) {
			if (msg.success) {
				$el.parent().children().prop('disabled', true).addClass('disabled');
				refreshNotifications();
			}
		});
	}));
});