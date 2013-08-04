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

	<form method="post" action="options.php" class="ab-press-settings">
		
	<?php 
		$license 	= get_option( 'ab_press_license_key' );
		$status 	= get_option( 'ab_press_license_status' );
		settings_fields('ab_press_license'); 
	?>

	<div class="ab-press-group">
		<label class="ab-press-label" for="ab_press_license_key"><?php _e('Enter your license key'); ?> <span class="description">(required)</span></label>
		<div class="ab-press-controls">
			<input id="ab_press_license_key" name="ab_press_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
		</div>
	</div>
	
	<?php if( false !== $license ) { ?>

		<div class="ab-press-group">
			<label class="ab-press-label" for="name"><?php _e('Activate License'); ?><span class="description">(required)</span></label>
			<div class="ab-press-controls">
				<?php if( $status !== false && $status == 'valid' ) { ?>
					<span style="color:green;"><?php _e('active'); ?></span>
					<?php wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
					<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License'); ?>"/>
				<?php } else {
					wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
					<input type="submit" class="button-secondary" name="edd_license_activate" value="<?php _e('Activate License'); ?>"/>
				<?php } ?>
			</div>
		</div>

	<?php } ?>

	<?php submit_button(); ?>

</form>

</div>