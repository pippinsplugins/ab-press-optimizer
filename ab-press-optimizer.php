<?php
/**
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 *
 * @wordpress-plugin
 * Plugin Name: AB Press Optimizer
 * Plugin URI:  http://ABPressOptimizer.com
 * Description: AB Press Optimizer A/B testing integrated directly into your WordPress site. Quickly and easily create dozens of different versions of your images, buttons, content blocks, forms and headlines.
 * Version:     1.0.4
 * Author:      Ivan Lopez
 * Author URI:  http://ABPressOptimizer.com
 * Text Domain: ab-press-optimizer-locale
 *
 * ------------------------------------------------------------------------
 * Copyright 2013 AB Press Optimizer (http://ABPressOptimizer.com)
 *
 **/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'ABPO_PATH', plugin_dir_path( __FILE__ ) );

require_once( plugin_dir_path( __FILE__ ) . 'class-ab-press-optimizer.php' );
require_once( plugin_dir_path( __FILE__ ) . '/includes/functions.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'ABPressOptimizer', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ABPressOptimizer', 'deactivate' ) );

// this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
define( 'AB_PRESS_STORE_URL', 'http://abpressoptimizer.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'AB_Press_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/includes/AB_Press_SL_Plugin_Updater.php' );
}

ABPressOptimizer::get_instance();

// retrieve our license key from the DB
$license_key = trim( get_option( 'ab_press_license_key' ) );
				

if(get_option('ab_press_license_type'))
{
	define( 'AB_PRESS_ITEM_NAME', get_option('ab_press_license_type') );

	// setup the updater
	$edd_updater = new AB_Press_SL_Plugin_Updater( AB_PRESS_STORE_URL, __FILE__, array( 
			'version' 	=> '1.0.4', 				// current version number
			'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
			'item_name' => AB_PRESS_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Ivan Lopez'  // author of this plugin
		)
	);
}
else
{
	if(get_option( 'ab_press_license_key' ))
	{
		update_option('ab_press_license_type', ABPressOptimizer::ab_press_get_license());
	}
}

if(!isset($_SESSION) && is_admin()) {
   session_start();
}

