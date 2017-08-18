<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.patricelaurent.net
 * @since      1.0.0
 *
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wp_Fftt
 * @subpackage Wp_Fftt/includes
 * @author     Patrice LAURENT <laurent.patrice@gmail.com>
 */
class Wp_Fftt {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wp_Fftt_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * @var string $appId ID de l'application fourni par la FFTT (ex: AM001)
	 */
	protected $appId;

	/**
	 * @var string $appKey Mot de passe fourni par la FFTT
	 */
	protected $appKey;

	/**
	 * @var string $serial Serial de l'utilisateur
	 */
	protected $serial;

	/**
	 * @var string $ipSource
	 */
	protected $ipSource;

	/**
	 * @array mixed|void
	 */
	protected $options;

	protected $cache;

	protected $api;

	protected $wpfftt_slug;


	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		session_start();
		$this->options = get_option( 'wp_fftt_settings' );
		$this->plugin_name = 'wp-fftt';
		$this->version = '1.0.0';
		$this->appId = isset($this->options['wp_fftt_login']) ? $this->options['wp_fftt_login'] : false;
		$this->appKey =  isset($this->options['wp_fftt_password']) ? $this->options['wp_fftt_password'] : false;
		$this->club_id =  isset($this->options['wp_fftt_club_id']) ? $this->options['wp_fftt_club_id'] : false;
		$this->wpfftt_slug = isset($this->options['wp_fftt_slug']) ?  $this->options['wp_fftt_slug']  : false;

		$this->load_dependencies();
		$this->cache = new CacheService();
		if (empty($_SESSION['serial'])) {
			$_SESSION['serial'] = ffttAPI::generateSerial();
		}
		$this->api = new ffttAPI($this->appId, $this->appKey, $this->cache);
		$this->api->setSerial($_SESSION['serial']);
		$this->api->initialization();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Fftt_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Fftt_i18n. Defines internationalization functionality.
	 * - Wp_Fftt_Admin. Defines all hooks for the admin area.
	 * - Wp_Fftt_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-fftt-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-fftt-i18n.php';

		/**
		 * FFTT API
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/ffttAPI/CacheService.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/ffttAPI/ffttAPI.php';

		/**
		 * Widgets
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/widgets/widgets.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-fftt-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wp-fftt-public.php';

		$this->loader = new Wp_Fftt_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Fftt_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wp_Fftt_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Fftt_Admin( $this->get_plugin_name(), $this->get_version(), $this->getOptions(), $this->getWpffttSlug() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_admin, 'init_action' );
		$this->loader->add_action( 'admin_head-post.php', $plugin_admin, 'wpfftt_admin_head' );
		$this->loader->add_action( 'admin_head-post-new.php', $plugin_admin, 'wpfftt_admin_head' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'wp_fftt_add_admin_menu' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_fftt_settings_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'add_nav_menu_meta_boxes' );
		$this->loader->add_action( 'updated_option_wp_fftt_settings', $plugin_admin, 'update_wpfftt_options_action',10,2 );
		$this->loader->add_filter( 'customize_nav_menu_available_item_types', $plugin_admin, 'register_customize_nav_menu_item_types'  );
		$this->loader->add_filter( 'customize_nav_menu_available_items', $plugin_admin, 'register_customize_nav_menu_items', 10, 4  );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wp_Fftt_Public( $this->get_plugin_name(), $this->get_version(), $this->getOptions(), $this->getApi(), $this->getWpffttSlug() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'init', $plugin_public, 'rewrite' );
		$this->loader->add_filter('query_vars', $plugin_public, 'query_vars');
		$this->loader->add_filter( 'the_content', $plugin_public, 'insert_content' );
		$this->loader->add_filter( 'the_title', $plugin_public, 'set_page_title',10,2 );
		$this->loader->add_filter( 'body_class', $plugin_public, 'add_body_class' );

		$this->loader->add_action( 'wp_ajax_nopriv_wpfftt_ajax_detail_party', $plugin_public, 'get_score_from_ajax' );
		$this->loader->add_action( 'wp_ajax_wpfftt_ajax_detail_party', $plugin_public, 'get_score_from_ajax' );
		$this->loader->add_action( 'widgets_init', $plugin_public, 'widgets_init' );

		//$this->loader->add_filter( 'bcn_after_fill', $plugin_public, 'wpfftt_bcn_breadcrumb_url');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wp_Fftt_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	public function getAppId() {
		return $this->appId;
	}

	public function getAppKey() {
		return $this->appKey;
	}

	public function getClubId() {
		return $this->club_id;
	}

	public function getOptions(){
		return $this->options;
	}

	public function getCache(){
		return $this->cache;
	}

	public function getApi(){
		return $this->api;
	}

	public function getWpffttSlug(){
		return $this->wpfftt_slug;
	}


}
