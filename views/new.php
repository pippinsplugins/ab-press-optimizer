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
		<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/banner-772x250.png') ?>">
	</div>

	<?php screen_icon('ab-press-optimizer'); ?>
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>

	<p>Nam vitae urna urna. Quisque lectus lacus, hendrerit eget lobortis at, aliquam nec dolor. Integer tincidunt pharetra sapien non volutpat. Pellentesque quis egestas dolor, vitae molestie tortor. Vestibulum eget odio tortor. Suspendisse euismod aliquet ante et congue. Vivamus mattis ac urna a semper. Mauris tempor neque non tristique tristique. Aliquam sit amet mi et mi dictum condimentum pellentesque vel eros.</p>

	<form action="" method="post" enctype="multipart/form-data" class="experimentForm">
		<?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('abpo-new-experiment'); ?>
		
		<div class="ab-press-group">
			<label class="ab-press-label" for="name">Experiment Name</label>
			<div class="ab-press-controls">
				<input type="text" id="name" class="regular-text">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="description">Experiment Description</label>
			<div class="ab-press-controls">
				<textarea name="description" id="description" class="regular-text"></textarea>
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="status">Status</label>
			<div class="ab-press-controls">
				<select name="status" id="status">
					<option value="running">Running</option>
					<option value="paused">Paused</option>
					<option value="Complete">Complete</option>
				</select>
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="startDate">Start Date</label>
			<div class="ab-press-controls">
				<input type="text" id="startDate" >
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="endDate">End Date</label>
			<div class="ab-press-controls">
				<input type="text" id="endDate" >
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goal">Goal</label>
			<div class="ab-press-controls">
				<input type="text" id="goal" class="regular-text">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goalTrigger">Goal Trigger</label>
			<div class="ab-press-controls">
				<select name="status" id="goalTrigger">
					<option value="page">Page View</option>
					<option value="clickEvent">Click Event</option>
					<option value="form">Submit a Form</option>
					<option value="revenue">Generate Revenue</option>
				</select>
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goal">Goal</label>
			<div class="ab-press-controls">
				<input type="text" id="goal" class="regular-text">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="url">URL</label>
			<div class="ab-press-controls">
				<input type="text" id="url" class="regular-text">
			</div>
		</div>

		<h3>Experiment Variations</h3>

		<a href="" class="button">Add Variation</a>


		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Experiment">
		</p>


	</form>

	<!-- TODO: Provide markup for your options page here. -->
</div>