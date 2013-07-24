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
 * Description: Easy A/B testing from within your WordPress site.  Create an experiment to test any part of your post or page. Try new headlines, buttons and call to actions. View real time metrics to see what experiments are converting best and are statistically significant. 
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


require_once( plugin_dir_path( __FILE__ ) . 'class-ab-press-optimizer.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'ABPressOptimizer', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'ABPressOptimizer', 'deactivate' ) );

ABPressOptimizer::get_instance();