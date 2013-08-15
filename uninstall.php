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

delete_option('ab_press_optimizer_version');
delete_option('ab_press_license_key');
delete_option('ab_press_license_status');
delete_option('ab_press_hide_pointer');
delete_option('ab_press_license_type');

require_once( plugin_dir_path( __FILE__ ) . 'class-ab-press-optimizer.php' );

global $wpdb;

$sql = "DROP TABLE " . ABPressOptimizer::get_table_name('experiment') ;
$wpdb->query($sql);

$sql = "DROP TABLE " . ABPressOptimizer::get_table_name('variations') ;
$wpdb->query($sql);

