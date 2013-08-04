<?php
/**
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 *
 * @wordpress-plugin
 * Plugin Name: A/B Press Optimizer
 * Plugin URI:  http://ABPressOptimizer.com
 * Description: AB Press Optimizer A/B testing integrated directly into your WordPress site. Quickly and easily create dozens of different versions of your images, buttons and headlines. Showing you which versions will increase your bottom line. 
 * Version:     1.0.0
 * Author:      Ivan Lopez
 * Author URI:  ttp://ABPressOptimizer.com
 * Text Domain: ab-press-optimizer-locale
 *
 * ------------------------------------------------------------------------
 * Copyright 2013 A/b Press Optimizer (http://ABPressOptimizer.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
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

// the name of your product. This should match the download name in EDD exactly
define( 'AB_PRESS_ITEM_NAME', 'AB Press Optimizer' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file

if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/includes/EDD_SL_Plugin_Updater.php' );
}

ABPressOptimizer::get_instance();

// retrieve our license key from the DB
$license_key = trim( get_option( 'ab_press_license_key' ) );

// setup the updater
$edd_updater = new EDD_SL_Plugin_Updater( AB_PRESS_STORE_URL, __FILE__, array( 
		'version' 	=> '1.0', 				// current version number
		'license' 	=> $license_key, 		// license key (used get_option above to retrieve from DB)
		'item_name' => AB_PRESS_ITEM_NAME, 	// name of this plugin
		'author' 	=> 'Ivan Lopez'  // author of this plugin
	)
);