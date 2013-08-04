<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

require_once( plugin_dir_path( __FILE__ ) . 'class-ab-press-optimizer.php' );

delete_option('ab_press_optimizer_version');

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$sql = "DROP TABLE " . ABPressOptimizer::get_table_name('experiment') ;
dbDelta($sql);

$sql = "DROP TABLE " . ABPressOptimizer::get_table_name('variations') ;
dbDelta($sql);


wp_clear_scheduled_hook( 'ab_press_experiment_refresh' );