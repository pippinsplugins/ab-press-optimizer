(function() {


	tinymce.create('tinymce.plugins.abPressPlugin', {
		init : function(ed, url) {
			url = url.replace('/js', '');
			// Register commands
			ed.addCommand('mcebutton', function() {
				ed.windowManager.open({
					file : url + '/views/shortcode_popup.php', // file that contains HTML for our modal window
					width : 500 + parseInt(ed.getLang('button.delta_width', 0)), // size of our window
					height : 270 + parseInt(ed.getLang('button.delta_height', 0)), // size of our window
					inline : true,
				}, {
					plugin_url : url
				});
			});
			 
			// Register buttons
			ed.addButton('ab_press_button', {title : 'Insert An AB Press Optimizer Experiment', cmd : 'mcebutton', image: url + '/assets/abPress-tinymc-icon.png' });
		},
		 
		getInfo : function() {
			return {
				longname : 'Insert An AB Press Optimizer Experiment',
				author : 'Ivan Lopez',
				authorurl : 'http://abpressoptimizer.com',
				infourl : 'http://abpressoptimizer.com',
				version : tinymce.majorVersion + "." + tinymce.minorVersion
			};
		}
	});
	 
	// Register plugin
	// first parameter is the button ID and must match ID elsewhere
	// second parameter must match the first parameter of the tinymce.create() function above
	tinymce.PluginManager.add('ab_press_button', tinymce.plugins.abPressPlugin);

})();