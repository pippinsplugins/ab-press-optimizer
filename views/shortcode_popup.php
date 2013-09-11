<?php
	$ab_press_url = ab_press_url();

	function ab_press_url()
	{
		$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
		$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s;
		$port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
		$url = $protocol . "://" . $_SERVER['SERVER_NAME'] . $port . $_SERVER['REQUEST_URI'];
		$url = explode("wp-content", $url);
		return $url[0] ;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Insert AB Press Optimizer Experiment</title>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.1/jquery.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo $ab_press_url; ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>


<style type="text/css">

	 .button,  .button-primary,  .button-secondary {
		display: inline-block;
		text-decoration: none;
		font-size: 12px;
		line-height: 23px;
		height: 24px;
		margin: 0;
		padding: 0 10px 1px;
		cursor: pointer;
		border-width: 1px;
		border-style: solid;
		-webkit-border-radius: 3px;
		-webkit-appearance: none;
		border-radius: 3px;
		white-space: nowrap;
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.button-primary {
		background-color: #21759B;
		background-image: -webkit-gradient(linear,left top,left bottom,from(#2A95C5),to(#21759B));
		background-image: -webkit-linear-gradient(top,#2A95C5,#21759B);
		background-image: -moz-linear-gradient(top,#2A95C5,#21759B);
		background-image: -ms-linear-gradient(top,#2A95C5,#21759B);
		background-image: -o-linear-gradient(top,#2A95C5,#21759B);
		background-image: linear-gradient(to bottom,#2A95C5,#21759B);
		border-color: #21759B;
		border-bottom-color: #1E6A8D;
		-webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, 0.5);
		box-shadow: inset 0 1px 0 rgba(120, 200, 230, 0.5);
		color: #FFF !important;
		text-decoration: none;
		text-shadow: 0 1px 0 rgba(0, 0, 0, 0.1);
	}

	 .button-primary.hover,  .button-primary:hover,  .button-primary.focus,  .button-primary:focus {
		background-color: #278AB7;
		background-image: -webkit-gradient(linear,left top,left bottom,from(#2E9FD2),to(#21759B));
		background-image: -webkit-linear-gradient(top,#2E9FD2,#21759B);
		background-image: -moz-linear-gradient(top,#2E9FD2,#21759B);
		background-image: -ms-linear-gradient(top,#2E9FD2,#21759B);
		background-image: -o-linear-gradient(top,#2E9FD2,#21759B);
		background-image: linear-gradient(to bottom,#2E9FD2,#21759B);
		border-color: #1B607F;
		-webkit-box-shadow: inset 0 1px 0 rgba(120, 200, 230, 0.6);
		box-shadow: inset 0 1px 0 rgba(120, 200, 230, 0.6);
		color: #FFF;
		text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
	}

	 .button,  .button-secondary {
		background: #F3F3F3;
		background-image: -webkit-gradient(linear,left top,left bottom,from(#FEFEFE),to(#F4F4F4));
		background-image: -webkit-linear-gradient(top,#FEFEFE,#F4F4F4);
		background-image: -moz-linear-gradient(top,#FEFEFE,#F4F4F4);
		background-image: -o-linear-gradient(top,#FEFEFE,#F4F4F4);
		background-image: linear-gradient(to bottom,#FEFEFE,#F4F4F4);
		border-color: #BBB;
		color: #333;
		text-shadow: 0 1px 0 #FFF;
	}

	.button.hover,  .button:hover,  .button-secondary:hover,  .button.focus,  .button:focus,  .button-secondary:focus {
		background: #F3F3F3;
		background-image: -webkit-gradient(linear,left top,left bottom,from(#FFF),to(#F3F3F3));
		background-image: -webkit-linear-gradient(top,#FFF,#F3F3F3);
		background-image: -moz-linear-gradient(top,#FFF,#F3F3F3);
		background-image: -ms-linear-gradient(top,#FFF,#F3F3F3);
		background-image: -o-linear-gradient(top,#FFF,#F3F3F3);
		background-image: linear-gradient(to bottom,#FFF,#F3F3F3);
		border-color: #999;
		color: #222;
	}

	select{
		padding: 2px !important;
		height: 2em !important;
		font-size: 12px !important;
	}

	.hasExperiments, .noExperiments{
		display: none;
	}

	.abLoader img {
		vertical-align: middle;
		margin-right: 10px;
	}
</style>

<script type="text/javascript">
 
var ButtonDialog = {
	local_ed : 'ed',
	init : function(ed) {
		ButtonDialog.local_ed = ed;
		tinyMCEPopup.resizeToInnerSize();
	},
	insert : function insertButton(ed) {
	 
		// Try and remove existing style / blockquote
		tinyMCEPopup.execCommand('mceRemoveNode', false, null);
		 
		var experiment = jQuery('#ab-press-selected').val();
		var option = jQuery('#ab-press-option').val();

		if(experiment == "")
		{
			alert('Please select an experiment');
			return false;
		}

		var output = '';
		
		// setup the output of our shortcode
		output = '[abpress ';
		output += 'id=' + experiment + ' ';

		if(option ==  "True")
			output += 'multipage=' + option + ' ';

		output += ']'+ButtonDialog.local_ed.selection.getContent({format: 'html'}) + '[/abpress]';

		tinyMCEPopup.execCommand('mceReplaceContent', false, output);
		 
		// Return
		tinyMCEPopup.close();
	}
};
tinyMCEPopup.onInit.add(ButtonDialog.init, ButtonDialog);

jQuery.get("<?php echo $ab_press_url;?>wp-admin/admin-ajax.php", {action:"ab-press-optimizer-get"}, function(result){
	
	console.log("<?php echo $ab_press_url;?>")
	if(result.length > 0){
		jQuery('.hasExperiments').show();
		jQuery('.abLoader').hide();
		for (var i = 0; i < result.length; i++) {
			jQuery('#ab-press-selected').append('<option value="'+result[i].id+'">'+result[i].name+'</option>');
		};
	}
	else
	{
		jQuery('.noExperiments').show();
		jQuery('.abLoader').hide();
	}
})
  
</script>

</head>
<body>

	<div style="font-family: sans-serif; font-size: 12px; line-height: 1.4em;">
		<div style="padding:15px 15px 0px 15px;">
	        <h3 style="color:#5A5A5A!important; margin-bottom:5px; font-family:Georgia,Times New Roman,Times,serif!important; font-size:1.8em!important; font-weight:normal!important;">
	        	Insert An Experiment
	        </h3>
	        <span>
	            Select an experiment below to add it to your post, page or custom post type.
	        </span>
	    </div>
		<div style="padding:15px 15px 0px 15px;">

			<div class="abLoader">
				<p><img src="../assets/experimentLoader.gif" >Loading Experiments</p>
			</div>
			
			<div class="noExperiments">
				<p>Please create a experiment</p>
				<a href="admin.php?page=abpo-new" target="_top"  class="button-primary">New Experiment</a>
			</div>

			<div class="hasExperiments">
				<select id="ab-press-selected"  class="ab_press_experiment">
					<option value="">Select a Experiment</option>
				</select>

				<p>Is this experiment across the site?</p>
				<select id="ab-press-option"  class="ab_press_experiment">
					<option value="False">False</option>
					<option value="True">True</option>
				</select>

				<div style="margin-top:10px">	
					<a href="javascript:ButtonDialog.insert(ButtonDialog.local_ed)"  class="button-primary">Insert Experiment</a>
					<a href="javascript:tinyMCEPopup.close();" class="button-secondary">Close</a>
				</div>
			</div>
			
		</div>
	</div>
</body>
</html>