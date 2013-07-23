<?php
/**
 * Fired when the plugin is uninstalled.
 *
 * @package   A/B Press Optimizer
 * @author    Ivan Lopez
 * @link      http://OneClickCreations.com
 * @copyright 2013 Ivan Lopez
 */

// If uninstall, not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// TODO: Define uninstall functionality here