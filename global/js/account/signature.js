/*! Crysandrea - Javascript for account/signature - Created at August 26, 2012*/
$(document).ready(function(){
$('#dropdown-2').appendTo('body');
	$('#change_title').on('click', function(){
		$("#change_title_tip").hide();
		$('#title').attr('disabled', false);
		$('#dropdown-2').hide();
		return false;
	});

	$('#forum_signature').bind('change keyup keydown', function(){
		$(this).val($(this).val().substring(0,450));
		$('#chars_left').css({color: 'black'}).text(450-parseInt($(this).val().length));
		if(450-parseInt($(this).val().length) == 0){
			$('#chars_left').css({color: 'red'})
		}
	});
});