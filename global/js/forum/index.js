/*! Crysandrea - Javascript for donate/index - Created at August 28, 2012*/

function getTimeRemaining(endtime) {
	var t = Date.parse(endtime) - Date.parse(new Date());
	var seconds = Math.floor((t / 1000) % 60);
	var minutes = Math.floor((t / 1000 / 60) % 60);
	var hours = Math.floor((t / (1000 * 60 * 60)) % 24);
	var days = Math.floor(t / (1000 * 60 * 60 * 24));
	return {
		'total': t,
		'days': days,
		'hours': hours,
		'minutes': minutes,
		'seconds': seconds
	};
}

function initializeClock(id, endtime) {
	var clock = $(id).find('strong');
	function updateClock() {
		var t = getTimeRemaining(endtime);
		
		clock.text(t.days + ' D, ' + ('0' + t.hours).slice(-2) + ':' + ('0' + t.minutes).slice(-2) + ':' + ('0' + t.seconds).slice(-2));
		
		if (t.total <= 0)
			clearInterval(timeinterval);
	}
	
	updateClock();
	var timeinterval = setInterval(updateClock, 1000);
}

var deadline1 = new Date(Date.parse('September 24 2016 12:00:00 GMT+1900'));
var deadline2 = new Date(Date.parse('September 28 2016 12:00:00 GMT+1900'));

$(document).ready(function(){
	$("#forum_tabs").tabs("div#forum_panes > div");
	// var gem_model = $('#show_gems').clone();
	$('body').append($('#show_bbcode').clone());
	$('#content #show_bbcode').remove();

	initializeClock('#time1', deadline1);
	initializeClock('#time2', deadline2);
});
