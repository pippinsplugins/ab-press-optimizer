<?php
/**
 * A/B Press Optimizer.
 *
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 */

/**
 * Plugin class.
 *
 * @package ab-press-optimizer
 * @author  Ivan Lopez
 */
class ABPressOptimizer {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'ab-press-optimizer';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		// Define custom functionality. Read more about actions and filters: http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
		add_action( 'TODO', array( $this, 'action_method_name' ) );
		add_filter( 'TODO', array( $this, 'filter_method_name' ) );

		// Add Experiment Link to plugin page
		add_filter('plugin_action_links',  array( $this, 'plugin_action_links') , 10, 2);
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		update_option('ab_press_optimizer_version', '1.0.0');
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public function deactivate( $network_wide ) {
		delete_option('ab_press_optimizer_version');
		//check for multisite
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	
		$this->create_experiment_table();
		$this->create_variations_table();
	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		wp_enqueue_style( $this->plugin_slug .'-admin-jquery-ui', plugins_url( 'css/jquery-ui.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-admin-validation', plugins_url( 'js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->plugin_slug . '-admin-validationMethod', plugins_url( 'js/additional-methods.js', __FILE__ ), array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
		wp_enqueue_script('jquery-ui-datepicker');
	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'css/public.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'js/public.js', __FILE__ ), array( 'jquery' ), $this->version );
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_menu_page(
			__( 'A/B Press Optimizer', $this->plugin_slug ),
			__( 'A/B Press ', $this->plugin_slug ),
			'administrator',
			'abpo-experiment',
			array( $this, 'display_plugin_experiment_page' ),
			plugin_dir_url( __FILE__ ) . '/assets/abPress-icon.png',
			1000
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'A/B Press Optimizer', $this->plugin_slug ),
			__( 'Experiments', $this->plugin_slug ),
			'administrator',
			'abpo-experiment',
			array( $this, 'display_plugin_experiment_page' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'A/B Press Optimizer Getting Started', $this->plugin_slug ),
			__( 'Getting Started', $this->plugin_slug ),
			'administrator',
			'abpo-gettingStarted',
			array( $this, 'display_plugin_getting_started' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'A/B Press Optimizer Settings', $this->plugin_slug ),
			__( 'Settings', $this->plugin_slug ),
			'administrator',
			'abpo-settings',
			array( $this, 'display_plugin_settings' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'New Experiment', $this->plugin_slug ),
			'New',
			'administrator',
			'abpo-new',
			array( $this, 'display_new_experiment' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'Detail Experiment', $this->plugin_slug ),
			"Detail",
			'administrator',
			'abpo-details',
			array( $this, 'display_detail_experiment' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'Edit Experiment', $this->plugin_slug ),
			"Edit",
			'administrator',
			'abpo-edit',
			array( $this, 'display_edit_experiment' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'Export Experiment', $this->plugin_slug ),
			"Export",
			'administrator',
			'abpo-export',
			array( $this, 'display_export_experiment' )
		);


	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function plugin_action_links($links, $file) {
		static $this_plugin;
 
		if (!$this_plugin) {
		    $this_plugin = plugin_basename('ab-press-optimizer/ab-press-optimizer.php');
		}
		
		if ($file == $this_plugin) {
		    $dashboard_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=abpo-experiment">Experiments</a>';
			array_unshift($links, $dashboard_link);
		}
 
    	return $links;
	}

	/**
	 * Render the Experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_experiment_page() {
		include_once( 'views/experiment.php' );
	}

	/**
	 * Render the Getting Started page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_getting_started() {
		include_once( 'views/gettingStarted.php' );
	}

	/**
	 * Render the Settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_settings() {
		include_once( 'views/settings.php' );
	}

	/**
	 * Render the new experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_new_experiment() {
		include_once( 'views/new.php' );
	}

	/**
	 * Render view experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_detail_experiment() {
		include_once( 'views/details.php' );
	}

	/**
	 * Render edit experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_edit_experiment() {
		include_once( 'views/edit.php' );
	}

	/**
	 * Render view experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_export_experiment() {
		include_once( 'views/export.php' );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *        Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    1.0.0
	 */
	public function action_method_name() {
		// TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    1.0.0
	 */
	public function filter_method_name() {
		// TODO: Define your filter hook callback here
	}

	/**
	 * Create Databes for plugin
	 */
	private function create_experiment_table() {
		$table_name = self::get_table_name('experiment');
		
		if (!$this->database_table_exists($table_name)) {
			$sql = "CREATE TABLE " . $table_name . " (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(250) NOT NULL DEFAULT '',
					description VARCHAR(500) NOT NULL DEFAULT '',
					status VARCHAR(25) NOT NULL DEFAULT '',
					start_date DATE NOT NULL DEFAULT '0000-00-00 00:00:00',
					end_date DATE NOT NULL DEFAULT '0000-00-00 00:00:00',
					goal VARCHAR(500) NOT NULL DEFAULT '', 
					goal_type VARCHAR(100) NOT NULL DEFAULT '',
					url VARCHAR(500) NOT NULL DEFAULT '',
					original_visits INT NOT NULL DEFAULT 0,
					original_convertions INT NOT NULL DEFAULT 0,
					date_created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
					);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	/**
	 * Create Databes for plugin
	 */
	private function create_variations_table() {
		$table_name = self::get_table_name('variations');
		
		if (!$this->database_table_exists($table_name)) {
			$sql = "CREATE TABLE " . $table_name . " (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					experiment_id INT NOT NULL,
					type VARCHAR(100) NOT NULL DEFAULT '',
					name VARCHAR(250) NOT NULL DEFAULT '',
					value VARCHAR(500) NOT NULL DEFAULT '',
					class VARCHAR(500) NOT NULL DEFAULT '',
					visits INT NOT NULL DEFAULT 0,
					convertions INT NOT NULL DEFAULT 0,
					date_created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
					);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	/**
	 * Return Table name
	 *
	 * @return String
	 */
	public static function get_table_name($name) {
		global $wpdb;
		return $wpdb->prefix . 'ab_press_optimizer_' . $name;
	}

	/**
	 * Check if database exist
	 *
	 * @return Boolean
	 */
	private function database_table_exists($table_name) {
		global $wpdb;
		return strtolower($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) == strtolower($table_name);
	}

}