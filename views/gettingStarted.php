<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 */
?>
<div class="wrap">

	<div class="ab-press-header">
		<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/ab-logo.png') ?>">
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>

	<h2 class="nav-tab-wrapper">
		<a href="admin.php?page=abpo-experiment" class="nav-tab">Experiments</a>
		<a href="" class="nav-tab  nav-tab-active">Getting Started</a>
		<a href="admin.php?page=abpo-settings" class="nav-tab">Settings</a>
	</h2>

	<h2>Quick Start Guide</h2>

	<ol>
		<li>Click "Add new"</li>
		<li>Enter an experiment name</li>
		<li>Select a start and end date</li>
		<li>Enter the goal of this experiment</li>
		<li>Select an experiment type (<a href="#goalTrigger">goal trigger</a>)</li>
		<li>If you've selected an experiment type other than "Click Event" select the page you want to trigger a conversion. (Please make sure that the page you select is the next chronological page to where you will be conducting your experiment )</li>
		<li>Click "Add Variation"</li>
		<li>Once you have created all the variations you wanted click "Save Experiment"</li>
		<li>To embed the experiment go to the page, post or custom post type and add the Ab Press Optimizer <a href="#shortcode">Shortcode</a>.</li>
	</ol>

	<h2 id="goalTrigger">Experiment Types (Goal Triggers)</h2>

	<h3>Page View</h3>
	<p>A page view experiment is for tracking when a user/customer visits a page for the first time. This is perfect for testing images, taglines and calls to action. This test can help you determine what elements on your page can get users further into your website for example you’re pricing page or features page.</p>

	<h3>Click Event</h3>
	<p>A Click Event experiment is for tracking when a user/customer clicks on an element for the first time. This is perfect for experimenting with button styles and calls to actions. This test can help you determine how to get users/customers to better interact with elements on your page.</p>

	<h3>Submit a Form </h3>
	<p>A Submit Form experiment is for tracking when a user/customer submits a form for the first time.  This is perfect for testing images, taglines and calls to action for your checkout page, newsletter signups, and membership signups.</p>

	<h2>Variation Types</h2>

	<h3>Text</h3>
	<p>Replace the text in a HTML element and/or apply custom CSS classes for styling. AB Press Optimizer currently supports the following tags a, p, div, span, section, and input.</p>

	<h3>HTML</h3>
	<p>Replace complete section of html with the html from the variation. </p>

	<h3>Image </h3>
	<p>Replace or add images as and/or apply custom CSS classes for styling. If your control is an image than the source of the image will be replace but if the image does not exist than we will generate the image tags. </p>

	<h2 id="shortcode">Embedding Experiments</h2>

	<h3>Shortcode</h3>

	<p>To embed your experiment into any post, page or custom post type simply wrap the element your trying to test with the Shortcode and pass it the experiment Id. If you would like to run an experiment across multiple pages simply add the multisite attribute ex:( multipage=True).</p>

	<p>EX: </p>
		
	<p>
		[abPress id=1] &#60;a href=""&#62;Link&#60;/a&#62; [/abPress]
	</p>

	<h3>PHP</h3>
	<p>If you need more flexibility and want to embed your experiment into one of your WordPress templates. Use our experiment function. That function takes two required parameters and one optional parameter; the first is your experiment id, the second is the element you're trying to test and the third is a Boolean for if the experiment will be ran across multiple pages. </p>

	<p>EX: </p>
		
	<p>
		&#60;?php echo ab_press_optimizer(1, '&#60;a href=""&#62;Link&#60;/a&#62;'); ?&#62;
	</p>

	<h3>Testing Conversions </h3>
	<p>In order to see your different variations you can add the URL parameter of "testing" to the URL. This has to be done on the first view of your experiment. If you have viewed the page with your experiment and would like to see it in testing mode please delete all your browser cookies.</p>

	<p>EX: </p>

	<p>www.MyDomain.com/page1?testing</p>

	<h2>Terms</h2>

	<h3>Visitors</h3>
	<p>Number of unique visitors who have taken part in the experiment. To count as part of the experiment, a visitor must first arrive on a page with the experiment.</p>

	<h3>Conversions</h3>
	<p>Number of unique visitors who have taken part in the experiment and the end goal for the experiment.</p>

	<h3>Conversion Rate</h3>
	<p>Percentage of visitors who reached the experiments end goal from the visitors who have taken part in the experiment.</p>

	<h3>Variations</h3>
	<p>Number of instances you're testing in an experiment.</p>

	<h3>ID</h3>
	<p>Unique identifier for a specific experiment.</p>

	<h3>Goal</h3>
	<p>A description of what the user needs to do in order to constitute a conversion.</p>

	<h3>Improvement</h3>
	<p>Percentage of improvement of the variation conversion rate compared to the original conversion rate. Improvement can be positive or negative.</p>

	<h3>Chance to beat original (Statistical Confidence)</h3>
	<p>Confidence level of the variation results (a value between 0%-100%) is a metric which indicates the confidence we have in a variation performing better compared to Control. When a Variation reaches the 95% threshold with a sample size of at least 30 visitors we declare it as a winner. </p>
</div>