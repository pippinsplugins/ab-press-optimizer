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
		<a href="admin.php?page=abpo-gettingStarted" class="nav-tab">Getting Started</a>
		<a href="" class="nav-tab  nav-tab-active">Settings</a>
	</h2>


	<!-- TODO: Provide markup for your options page here. -->

</div>