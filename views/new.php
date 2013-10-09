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
	<h2>New Experiment</h2>

	<p>Please fill in all the fields that are marked as required and create at least one variation. If you set your start date to be in the future then the experiment will be set to pause until that date arrives.</p>

	<?php
		if(isset($_SESSION['message']))
		{
			echo "<div id='message' class='error'><p>".$_SESSION['message']."</p></div>";
			ab_press_deleteMessage();
		}
	?>

	<form action="<?php echo admin_url( 'admin.php?page=abpo-new' ); ?>" method="post" enctype="multipart/form-data" class="ab-press-experimentForm">
		<?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('abpo-new-experiment'); ?>
		<input type="hidden" name="save" value="save">

		<div class="ab-press-group">
			<label class="ab-press-label" for="name">Experiment Name <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text" id="name" name="name" class="regular-text">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="description">Experiment Description</label>
			<div class="ab-press-controls">
				<textarea name="description" id="description" class="regular-text"></textarea>
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="startDate">Start Date <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text" name="startDate" id="startDate" class="ab-datepicker" >
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="endDate">End Date <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text"  name="endDate" id="endDate"  class="ab-datepicker" >
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goal">Goal <span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<input type="text" id="goal" name="goal" class="regular-text">
			</div>
		</div>

		<div class="ab-press-group">
			<label class="ab-press-label" for="goalTrigger">Goal Trigger</label>
			<div class="ab-press-controls">
				<select name="goalTrigger" id="goalTrigger">
					<option value="page">Page View</option>
					<option value="clickEvent">Click Event</option>
					<option value="clickEventAjax">Click Event Ajax</option>
					<option value="form">Submit a Form</option>
				</select>
			</div>
		</div>

		<div class="ab-press-group" id="ab-urlGroup">
			<label class="ab-press-label" for="url">URL <span class="description">(required)</span></label>
			<div class="ab-press-controls">

				<?php if ( function_exists('wp_nonce_field') ) wp_nonce_field('ajax_ab', 'ajax_ab_nonce'); ?>
				<input type="text" name="url" id="url" value="" class="regular-text">

				<div class="selectBox">
					<ul>

					</ul>
					<div class="loading">
						Loading Pages
						<img src="<?php echo plugins_url( 'ab-press-optimizer/assets/experimentLoader.gif') ?>">
					</div>
				</div>
			</div>
		</div>

		<h3>Experiment Variations</h3>

		<div class="variationContainer">

		</div>

		<a href="" class="button" id="addVariation">Add Variation</a>


		<p class="submit">
			<input type="submit" name="submit" id="submit" class="button button-primary" value="Save Experiment">
		</p>


	</form>

</div>