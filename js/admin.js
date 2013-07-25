(function ($) {
	"use strict";
	$(function () {
		
		//Add Variation Item
		$('#addVariation').on('click', function(e){
			e.preventDefault();
			var item = $('.variationContainer').append('<div class="variationItem">\
				<select name="type[]" id="type"><option value="text">Text</option><option value="html">HTML</option><option value="img">Image</option></select>\
				<label class="ab-press-variation-label" for="variation[]">Content</label>\
				<input type="text" name="variation[]" class="ab-press-variation">\
				<label class="ab-press-class-label" for="class[]">Element Class</label>\
				<input type="text" name="class[]" class="ab-press-class">\
				<a class="delete-button" href="#">Delete</a>\
				</div>');
			
			var validationInput = item.find('.ab-press-variation');
			$( validationInput ).rules( "add", { required: true });
		});

		//Variation Item Change Event
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
				item.find('.ab-press-variation').replaceWith('<input type="text" name="variation[]" class="ab-press-variation">');
			}

		});

		//Remove Variation Item
		$(document).on('click', ".delete-button", function(e){
			e.preventDefault();
			var item = $(this).parent();
			var validationInput = item.find('.ab-press-variation');
			$( validationInput ).rules( "remove");
			item.remove();
		});

		//Remove Variation Item
		$('#goalTrigger').on('change', function(){
			var type = $(this).find('option:selected').val();

			if(type == "clickEvent")
			{
				$('#ab-urlGroup').hide();
				$( validationInput ).rules( "remove");
			}
			else
			{
				$('#ab-urlGroup').show();
				$( validationInput ).rules( "add", { required: true });
			}
		});


		//Experiment Validation
		$('.ab-press-experimentForm').validate(
		{
			rules: {
			    name: {
			      	required: true,
			    },
			    startDate: {
			      	required: true,
			    },
			    endDate: {
			      	required: true,
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

	});
}(jQuery));

