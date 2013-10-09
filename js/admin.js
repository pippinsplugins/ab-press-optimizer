(function ($) {
	"use strict";
	$(function () {

		$('#toplevel_page_abpo-experiment li:nth-child(5), #toplevel_page_abpo-experiment li:nth-child(6), #toplevel_page_abpo-experiment li:nth-child(7), #toplevel_page_abpo-experiment li:nth-child(8), #toplevel_page_abpo-experiment li:nth-child(9)').hide();
		
		//Add Variation Item
		$('#addVariation').on('click', function(e){
			e.preventDefault();
			var item = $('.variationContainer').append('<div class="variationItem">\
				<input type="hidden" name="vId[]" value="" >\
				<input type="hidden" class="deleteInput" name="delete[]" value="false">\
				<select name="type[]" id="type"><option value="text">Text</option><option value="html">HTML</option><option value="img">Image</option></select>\
				<label class="ab-press-variation-label-name" for="variationName[]">Name</label>\
				<input type="text" name="variationName[]" class="ab-press-variation-name variationName">\
				<label class="ab-press-variation-label" for="variation[]">Content</label>\
				<input type="text" name="variation[]" class="ab-press-variation variation">\
				<label class="ab-press-class-label" for="class[]">Element Class</label>\
				<input type="text" name="class[]" class="ab-press-class">\
				<a class="delete-button as-remove-variation" href="#">Delete</a>\
				</div>');
		});

		//Variation Item Change Event
		$('.ab-is-html, .ab-hide-file').hide();
		$(document).on('change', "select#type", function(){
			
			var element = $(this);
			var type = element.find('option:selected').val();
			var item = element.parent();

			if(type == "text")
			{
				item.find('.ab-press-variation-label').html('Content');
				item.find('.ab-press-class-label').html('Element Class').show();
				item.find('.ab-press-class').show();
				if(item.find('.ab-press-file').length > 0)
				{
					item.find('.ab-press-file').replaceWith('<input type="text" name="variation[]" class="ab-press-variation">');
				}
				else
				{
					item.find('.ab-press-variation').replaceWith('<input type="text" name="variation[]" class="ab-press-variation">');
				}
			}
			else if(type == "html") 
			{
				item.find('.ab-press-variation-label').html('Mark Up');
				item.find('.ab-press-class-label').hide();
				item.find('.ab-press-class').hide();
				if(item.find('.ab-press-file').length > 0)
				{
					item.find('.ab-press-file').replaceWith('<textarea name="variation[]" class="ab-press-variation variation"></textarea>');
				}
				else
				{
					item.find('.ab-press-variation').replaceWith('<textarea name="variation[]" class="ab-press-variation variation"></textarea>');
				}
			}
			else
			{
				item.find('.ab-press-variation-label').html('Image');
				item.find('.ab-press-class-label').html('Element Class').show();
				item.find('.ab-press-class').show();
				item.find('.ab-press-variation').replaceWith('<input type="file" name="variationFile[]"  class="ab-press-file variationFile">');
			}

		});

		//Remove Image Item
		$(document).on('click', "a.as-remove-img", function(e){
			e.preventDefault();
			var item = $(this).parent();
			item.find('.ab-image-holder').hide();
			item.find('.as-remove-img').hide();
			item.find('.ab-hide-file').show();
		});

		//Remove Variation Item
		$(document).on('click', "a.as-remove-variation", function(e){
			e.preventDefault();
			var item = $(this).parent().remove();
		});

		//Hide Variation Item
		$(document).on('click', "a.as-remove-variation-hide", function(e){
			e.preventDefault();
			var item = $(this).parent().find('.deleteInput').val("true");
			var item = $(this).parent().hide();
		});


		//Hide URL 
		$('#goalTrigger').on('change', function(){
			var type = $(this).find('option:selected').val();

			if(type == "clickEvent" || type == "clickEventAjax")
			{
				$('#ab-urlGroup').hide();
				$( '#url' ).rules( "remove");
			}
			else
			{
				$('#ab-urlGroup').show();
				$( '#url' ).rules( "add", { required: true });
			}
		});

		$('.ab-no-url-onload').hide();

		
		//Experiment Validation
		jQuery.validator.addClassRules({
			variation:{
		    	required: true,
		    },
		    variationName:{
		    	required: true,
		    },
		    variationFile:{
		    	 required: true, 
		    	 extension:"jpg|jpeg|png|gif", 
		    	 filesize: 204800 
		    }
		});

		$('.ab-press-experimentForm').validate(
		{
			rules: {
			    name: {
			      	required: true,
			    },
			    startDate: {
			      	required: true,
			      	date: true
			    },
			    endDate: {
			      	required: true,
			      	date: true
			    },
			    goal: {
			      	required: true,
			    },
			    url: {
			      	required: true,
			    },
			   
			},
			errorPlacement: $.noop,
			highlight: function(element) { $(element).addClass('inputError');},
			success: function(element) {
			 $(element).closest('.ab-press-group').find('.inputError').removeClass('inputError');}
		 });

		//File size validation
		$.validator.addMethod('filesize', function(value, element, param) {
		    return this.optional(element) || (element.files[0].size <= param) 
		});

		//Event Dates
		$( "#startDate" ).datepicker({
	      numberOfMonths: 2,
	      minDate: '0',
	      onClose: function( selectedDate ) {
	        $( "#endDate" ).datepicker( "option", "minDate", selectedDate );
	      }
	    });
	    $( "#endDate" ).datepicker({
	      numberOfMonths: 2,
	      onClose: function( selectedDate ) {
	        $( "#startDate" ).datepicker( "option", "maxDate", selectedDate );
	      }
	    });

	var canLoad = false;
	var noMoreLoad = false;
	var currPage = 1;


	jQuery.post(
			ajaxurl ,
			{
				action : 'ab-press-optimizer-load-content',
				page: currPage,
				ajax_ab_nonce : jQuery('#ajax_ab_nonce').val()
			},
			function( response ) {
				for (var i = 0; i < response.length; i++) {
					$('.selectBox > ul').append('<li ref="'+response[i].permalink+'">'+response[i].title+'</li>')
				};
				canLoad = true;
				$('.loading').hide();
			},
			 "json"
		);

	
	$('.selectBox').scroll(function()
	{	

	    if($('.selectBox').scrollTop() == ($('.selectBox').find('ul').height() - 200) && canLoad && !noMoreLoad)
	    {	
	    	$('.loading').show();
	    	canLoad == false;
	    	currPage++;
	    	jQuery.post(
				ajaxurl ,
				{
					action : 'ab-press-optimizer-load-content',
					page: currPage,
					ajax_ab_nonce : jQuery('#ajax_ab_nonce').val()
				},
				function( response ) {
					for (var i = 0; i < response.length; i++) {
						$('.selectBox > ul').append('<li ref="'+response[i].permalink+'">'+response[i].title+'</li>')
					};
					if(!response) noMoreLoad = true;
					canLoad == true;
					$('.loading').hide();
				},
				 "json"
			);
	    }
	});


	$('.selectBox').on('click', 'li', function(e)
	{
		$('#url').val($(this).attr('ref'));
	});


	
	});
}(jQuery));

