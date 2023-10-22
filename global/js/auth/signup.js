var currentGender = 'm',
	mirror = true,
	equippedItems = {base: {id: 1, layer: 2}};

var renderer = PIXI.autoDetectRenderer(180, 270, {transparent: true});
var stage = new PIXI.Container();

function changeEquip(id, gender, type, layer) {
	if (typeof equippedItems[type] !== 'undefined' && equippedItems[type].id === id)
		delete equippedItems[type];
	else {
		equippedItems[type] = {
			id: id,
			layer: layer
		};
	}
	updateAvatar();
}

function updateAvatar() {
	stage.destroy();
	stage = new PIXI.Container(true);

	var itemOrder = {};
	for (var i in equippedItems)
		itemOrder[equippedItems[i].layer] = '/images/signup/' + currentGender + equippedItems[i].id + '.png';

	for (var o in itemOrder) {
		var tmp = PIXI.Sprite.fromImage(itemOrder[o]);
		if (mirror) {
			tmp.anchor.set(1, 0);
			tmp.scale.x = -1;
		}
		stage.addChild(tmp);
	}
}

updateAvatar();
requestAnimationFrame(animate);

function animate() {
    requestAnimationFrame(animate);
    renderer.render(stage);
}

jQuery.fn.extend({
    setHelpMsg: function(msg, c) {
		var el = $(this).parent().find('.help-block').removeClass('hb-success hb-error').text(msg);
		if (c) el.addClass('hb-' + c);
    }
});

$(document).ready(function() {
	$('#avatar').append(renderer.view);
	
	$('.choice_select').on('click', 'a', function() {
		var i = $(this);

		if ($(this).hasClass('selected')) {
			$(this).removeClass('selected');
		} else {
			$('.equippable[data-item-layer="'+i.data('item-layer')+'"]').removeClass('selected');
			$(this).addClass('selected');
		}

		changeEquip(i.data('item-id'), i.data('item-gender'), i.data('item-type'), i.data('item-layer'));
		return false;
	});
	
	$('#toggleGender').click(function() {
		currentGender = (currentGender === 'm') ? 'f' : 'm';
		updateAvatar();
	});

	$('#btn-next').click(function() {
		$('#avatarmaker').hide();
		$('#main-form').show();
		$('#btn-next').hide();
		$('#btn-back').show();
		$('#btn-submit').show();
	});

	$('#btn-back').click(function() {
		$('#notice').empty();
		$('#main-form').hide();
		$('#avatarmaker').show();
		$('#btn-back').hide();
		$('#btn-next').show();
		$('#btn-submit').hide();
	});

	var $su = $('#signup-username'),
		$se = $('#signup-email'),
		$sp = $('#signup-password'),
		$sr = $('#signup-repassword')
		$srr = $('#signup-referal');

	var toUn = toPw = toRp = toEm = toSrr = 0;

	$('#btn-submit').click(function(e) {
		e.preventDefault();
		if ($sr.val() !== $sp.val()) {
			$('#notice').empty().append('<div class="error"><h3><img src="/images/icons/stop.png"> Hold on! We had some troubles:</h3><ul><li>The passwords doesn\'t match</li></ul></div>');
			return;
		}
			
		var submit = $(this).prop('disabled', true);

		var itemList = [];
		for (var i in equippedItems)
			itemList.push(equippedItems[i].id);

		var data = jQuery.param($('#userinfo').serializeArray()) + '&' + jQuery.param({items: itemList, gender: currentGender});
		$.post('/auth/signup/', data, function(j) {
			if (typeof j.status !== 'undefined' && j.status === false) {
				$('#notice').empty().append('<div class="error"><h3><img src="/images/icons/stop.png"> Hold on! We had some troubles:</h3><ul>'+j.text+'</ul></div>');
				submit.prop('disabled', false);
			} else if (j.status) {
				$('#notice').empty().append('<div class="success"><h3><img src="/images/icons/yay.png"> Yay! You did it!!</h3><ul><li>'+j.text+'</li><li>Please wait one second while we redirect you!</li></ul></div>');
				$.post('/avatar/save/');
				window.setTimeout(function(){
					window.location.href = "/home/";
				}, 4000);
			}
		});
	});

	$su.keydown(function() {
		if ($su.val().length > 0) {
			clearTimeout(toUn);
			toUn = setTimeout(function() {
				$.post("/auth/validate/username", {q: $su.val()}, function(j) {
					if (typeof j.error === 'undefined')
						$su.setHelpMsg(j.success, "success");
					else
						$su.setHelpMsg(j.error, "error");
				});
			}, 500);
		}
	});

	$se.keydown(function() {
		if ($se.val().length > 0) {
			clearTimeout(toEm);
			toEm = setTimeout(function() {
				$.post("/auth/validate/email", {q: $se.val()}, function(j) {
					if (typeof j.error === 'undefined')
						$se.setHelpMsg(j.success, "success");
					else
						$se.setHelpMsg(j.error, "error");
				});
			}, 500);
		}
	});

	$srr.keydown(function() {
		if ($srr.val().length > 0) {
			clearTimeout(toSrr);
			toSrr = setTimeout(function() {
				$.post("/auth/validate/referal", {q: $srr.val()}, function(j) {
					if ($srr.val())
						return $srr.setHelpMsg(j.msg, j.status);
					$srr.setHelpMsg("If someone invited you here, type here his/her username");
				});
			}, 500);
		} else {
			$srr.setHelpMsg("If someone invited you here, type here his/her username");
		}
	});
	
	$sp.keydown(function() {
		clearTimeout(toPw);
		toPw = setTimeout(function() {
			if ($sp.val().length > 5) {
				$sp.setHelpMsg("Good job! Your password is OK.", "success");
				$sr.closest('.control-group').slideDown('fast');
				console.log($sr.closest('.control-group'));
			} else {
				if ($sp.val().length > 0) {
					$sp.setHelpMsg("Password must be at least 6 characters long. " + (6 - $sp.val().length) + ' more to go', "error");
				} else {
					$sp.setHelpMsg("");
				}
				$sr.closest('.control-group').slideUp('fast');
			}
			checkRePassword();
		}, 500);
	});

	$sr.keydown(function() {
		clearTimeout(toRp);
		toRp = setTimeout(checkRePassword, 500);
	});
	
	function checkRePassword() {
		if (!$sr.val())
			return $sr.setHelpMsg("Retype your password");
		
		if (!$sp.val())
			return $sr.setHelpMsg("Hey, you're doing it in the wrong order...", "error");

		if ($sr.val() !== $sp.val()) {
			$sr.setHelpMsg("The passwords doesn't match... Why don't you try again?", "error");
		} else {
			if ($sp.val().length < 6) {
				$sr.setHelpMsg("Well... You managed to not read the message above...", "error");
			} else {
				$sr.setHelpMsg("Yey!! Good job!", "success");
			}
		}
	}
});