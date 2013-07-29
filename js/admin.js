(function ($) {
	"use strict";
	$(function () {

		$('#toplevel_page_abpo-experiment li:nth-child(5), #toplevel_page_abpo-experiment li:nth-child(6), #toplevel_page_abpo-experiment li:nth-child(7), #toplevel_page_abpo-experiment li:nth-child(8)').hide();
		
		//Add Variation Item
		$('#addVariation').on('click', function(e){
			e.preventDefault();
			var item = $('.variationContainer').append('<div class="variationItem">\
				<input type="hidden" name="vId[]" value="" >\
				<input type="hidden" class="deleteInput" name="delete[]" value="false">\
				<select name="type[]" id="type"><option value="text">Text</option><option value="html">HTML</option><option value="img">Image</option></select>\
				<label class="ab-press-variation-label" for="variation[]">Content</label>\
				<input type="text" name="variation[]" class="ab-press-variation">\
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
				item.find('.ab-press-variation').replaceWith('<input type="text" name="variation[]" class="ab-press-variation">');
			}
			else if(type == "html") 
			{
				item.find('.ab-press-variation-label').html('Mark Up');
				item.find('.ab-press-class-label').hide();
				item.find('.ab-press-class').hide();
				item.find('.ab-press-variation').replaceWith('<textarea name="variation[]" class="ab-press-variation"></textarea>');
			}
			else
			{
				item.find('.ab-press-variation-label').html('Image');
				item.find('.ab-press-class-label').html('Element Class').show();
				item.find('.ab-press-class').show();
				item.find('.ab-press-variation').replaceWith('<input type="file" name="variationFile[]"  class="ab-press-file">');

			}

		});

		//Remove Image Item
		$(document).on('click', "a.as-remove-img", function(e){
			e.preventDefault();
			var item = $(this).parent();
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

			if(type == "clickEvent")
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
			    variation:{
			    	required: true,
			    },
			    variationFile:{
			    	 required: true, 
			    	 extension:"jpg|jpeg|png|gif", 
			    	 filesize: 204800 
			    }

			},
			//errorPlacement: $.noop,
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


		

	
	});
}(jQuery));

