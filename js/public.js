(function ($) {
	"use strict";
	$(function () {

		$(document).on('click', '.ab-press-action', function(e){
			
			var clickInfo  = $(this).attr('abpress');
			createCookie('_ab_press_click', clickInfo, 1);
		});

		if(readCookie('_ab_press_click') != null)
		{
			var temp = readCookie('_ab_press_click');
			temp = temp.split('-');


			jQuery.post(
				abPressAjax.ajaxurl,
				{
					action : 'ab-press-optimizer-submit',
					experiment : temp[0],
					variation : temp[1],
					_wpnonce : abPressAjax.abpresNonce
				},
				function( response ) {

					eraseCookie('_ab_press_click');
				}
			);
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