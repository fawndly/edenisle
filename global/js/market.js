jQuery.fn.highlight = function () {
    $(this).each(function () {
        var el = $(this);
        $("<div/>")
        .width(el.outerWidth())
        .height(el.outerHeight())
        .css({
            "position": "absolute",
            "left": el.offset().left,
            "top": el.offset().top,
            "background-color": "#ffff99",
            "opacity": ".7",
            "z-index": "9999999"
        }).appendTo('body').fadeOut(1000).queue(function () { $(this).remove(); });
    });
}

function lazyLoadVisibleImages() {
	$('img.lazy').lazy({
	  visibleOnly: true,
	  threshold: 0,
	  throttle: 500,
	  effect: 'fadeIn',
	  delay: 100
	});
}

$(document).ready(function(){
	var config = { avatar_tabs: "#avatar_items" }
	var Market = {
		selectedItem: {}
	}

  	lazyLoadVisibleImages();

  	$('.time-left-for-bid').each(function() {
  	  var $this = $(this), finalDate = new Date().getTime() + (parseInt($(this).data('countdown-from')) * 1000);
  	  $this.countdown(finalDate, function(event) {
  	    $this.html("Auction ends in: "+ event.strftime('%-H:%M:%S'));
  	  });
  	});

	$(config.avatar_tabs+' a').click(function (e) {
		e.preventDefault();
		$(this).tab('show');
		lazyLoadVisibleImages();
	});

	$('a[data-type="inventory"]').on('click', function(){
		var self = $(this);
		$('.selected_item').removeClass('selected_item');
		self.addClass('selected_item');
		$("#market_item_key").val(self.attr('data-key'));

		Market.selectedItem = {
			thumb: self.find('img').attr('src'),
			name: self.attr('data-original-title')
		}
		renderSelectedItem(Market.selectedItem);

		return false;
	});

	$("#listing_type").on('change', function(){
		var self = $(this);
		$(".listing-settings").addClass('hide');
		$("." + self.val() + "-settings").removeClass('hide');

		console.log(self.val())
	});

	function renderSelectedItem(selectedItem) {
		var chosenItem = $("#chosen_item");
		chosenItem.removeClass("hide");
		chosenItem.find("#item-image").attr('src', selectedItem.thumb);
		chosenItem.find("#item-name").text(selectedItem.name);
	}

  	$(config.avatar_tabs+' a:first').tab('show');

  	function updateBidListing(new_bid_data) {
  		var offerRow = $("#offer_" + new_bid_data.offer_id);

  		// concerning the "Your bid label"
  		var yourBidLabel = $('<span class="label label-warning">Your bid!</span>');
		offerRow.find(".listing-desc .label.label-warning").remove();
  		if(new_bid_data.last_bidder_id == RealtimeSetup.userId) {
  			offerRow.find(".listing-desc").prepend(yourBidLabel);
  		}

  		// concerning the price list
		offerRow.find(".total-price").text(new_bid_data.price);
		offerRow.find(".last_bid_by img").attr("src", "/images/avatars/"+new_bid_data.last_bidder_id+"_headshot.png?" + Math.floor(Date.now() / 1000));
		offerRow.find(".last_bid_by a").attr("href", "/user/"+encodeURIComponent(new_bid_data.bidder_username));
		offerRow.find(".last_bid_by a").text(new_bid_data.bidder_username);
		offerRow.find("input.new_bid_offer").val(new_bid_data.min_bid);
    if (new_bid_data.new_countdown_seconds){
      var $bidTimestamp = offerRow.find(".time-left-for-bid");
      var finalDate = new Date().getTime() + (parseInt(new_bid_data.new_countdown_seconds) * 1000);
      $bidTimestamp.countdown(finalDate, function(event) {
        $bidTimestamp.html("Auction ends in: "+ event.strftime('%-H:%M:%S'));
      });
    }

		offerRow.highlight();
  	}

  	// realtime bidding
  	if (Pusher) {
  		// ajax bidding
  		$(".bid-form").on('submit', function(e) {
  			var $this = $(this);
  		    $.ajax({
				type: "POST",
				url: $this.attr('action'),
				data: $this.serialize(),
				success: function(){
					$this.find('.btn.btn-warning.btn-small').button('reset');
				}
	        });

  		    e.preventDefault(); // avoid to execute the actual submit of the form.
  		});

  		var pusher = new Pusher(RealtimeSetup.token, {
  			wsHost: RealtimeSetup.host,
  			wsPort: RealtimeSetup.port,
  			wssPort: RealtimeSetup.port,
  			encrypted: false,
  		});

  		var channel = pusher.subscribe(RealtimeSetup.channel);
  		channel.bind('new_bid', function(data) {
  			updateBidListing(data);
  		});
  	} else {
  		console.error('Pusher not loaded')
  	}
});
