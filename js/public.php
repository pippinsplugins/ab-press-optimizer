<?php 
	require('../../../../wp-load.php'); 
	$nonce = wp_create_nonce( 'abpress-click-event' );

?>

(function ($) {
	"use strict";
	$(function () {
		var url = "<?php echo plugins_url() . '/ab-press-optimizer/includes/click_event_handler.php';  ?>";
		$(document).on('click', '.ab-press-action', function(e){
			
			var clickInfo  = $(this).attr('abpress');
			clickInfo  += "-<?php echo $nonce; ?>";
			createCookie('_ab_press_click', clickInfo, 1);
		});

		if(readCookie('_ab_press_click') != null)
		{
			var temp = readCookie('_ab_press_click');
			temp = temp.split('-');
			var data = {
					experiment: temp[0],
					variation: temp[1],
					nonce: temp[2],
				}
			jQuery.post(url, data , function(result) {
				eraseCookie('_ab_press_click');
			});
		}

	});
}(jQuery));

function createCookie(name,value,days) {
  if (days) {
    var date = new Date();
    date.setTime(date.getTime()+(days*24*60*60*1000));
    var expires = "; expires="+date.toGMTString();
  }
  else var expires = "";
  document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
  var nameEQ = name + "=";
  var ca = document.cookie.split(';');
  for(var i=0;i < ca.length;i++) {
    var c = ca[i];
    while (c.charAt(0)==' ') c = c.substring(1,c.length);
    if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
  }
  return null;
}

function eraseCookie(name) {
  createCookie(name,"",-1);
}