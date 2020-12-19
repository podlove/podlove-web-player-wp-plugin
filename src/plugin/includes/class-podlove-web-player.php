<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    5.0.2
	 * @access   protected
	 * @var      Podlove_Web_Player_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    5.0.2
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    5.0.2
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
  protected $version;

  protected $renderer;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    5.0.2
	 */
	public function __construct() {
		$this->version = PODLOVE_WEB_PLAYER_VERSION;

		$this->plugin_name = 'podlove-web-player';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_block_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Podlove_Web_Player_Loader. Orchestrates the hooks of the plugin.
	 * - Podlove_Web_Player_i18n. Defines internationalization functionality.
	 * - Podlove_Web_Player_Admin. Defines all hooks for the admin area.
	 * - Podlove_Web_Player_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    5.0.2
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-podlove-web-player-admin.php';

    /**
		 * The class responsible for rednering player shortcode
		 */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-shortcode.php';

    /**
		 * The class responsible for rednering player enclosure
		 */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-enclosure.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-podlove-web-player-public.php';

    /**
		 * The class responsible for loading and saving plugin options
		 */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-options.php';

    /**
     * The class responsible for defining the Admin REST API
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-admin-api.php';

    /**
     * The class responsible for defining the embed data
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-embed-data.php';

    /**
     * The class responsible for defining the Public Embed REST API
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-embed-api.php';

    /**
     * The class responsible for plugin interoperability
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-podlove-web-player-interoperability.php';

    /**
     * The class responsible for defining the block
     */
    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'block/class-podlove-web-player-block.php';


    $this->loader = new Podlove_Web_Player_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Podlove_Web_Player_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    5.0.2
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Podlove_Web_Player_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    5.0.2
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Podlove_Web_Player_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
    $this->loader->add_action( 'admin_menu', $plugin_admin, 'register_menu_page' );
    $this->loader->add_action( 'rest_api_init', $plugin_admin, 'add_routes' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    5.0.2
	 * @access   private
	 */
	private function define_public_hooks() {
    $plugin_public = new Podlove_Web_Player_Public( $this->get_plugin_name(), $this->get_version() );
    $plugin_block = new Podlove_Web_Player_Block( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'init', $plugin_public, 'register_shortcodes' );
    $this->loader->add_action( 'init', $plugin_block, 'register_block' );
    $this->loader->add_action( 'rest_api_init', $plugin_public, 'add_routes' );
    $this->loader->add_action( 'wp', $plugin_public, 'register_enclosure' );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
    $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
  }

	/**
	 * Register all of the hooks related to the block functionality
	 * of the plugin.
	 *
	 * @since    5.0.2
	 * @access   private
	 */
  private function define_block_hooks() {
    $plugin_block = new Podlove_Web_Player_Block( $this->get_plugin_name(), $this->get_version() );

    $this->loader->add_action( 'enqueue_block_editor_assets', $plugin_block, 'enqueue_scripts' );
  }

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    5.0.2
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     5.0.2
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     5.0.2
	 * @return    Podlove_Web_Player_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     5.0.2
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
  }
}
