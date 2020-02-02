<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/admin
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
   * Plugin api
   */
  private $api;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->api = new Podlove_Web_Player_Admin_API($plugin_name, $version);
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_styles() {
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/podlove-web-player-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    4.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name . '-configurator', plugin_dir_url( __FILE__ ) . 'js/app.js', array(), $this->version, true );

    wp_localize_script( $this->plugin_name . '-configurator', 'PODLOVE', array(
        'i18n' => Podlove_Web_Player_i18n::translations(),
        'api' => $this->api->routes()
      )
    );
  }

  /**
	 * Creates the configuration page
	 *
	 * @since 		1.0.0
	 * @return 		void
	 */
	public function page_settings() {
		include( plugin_dir_path( __FILE__ ) . 'partials/podlove-web-player-admin-display.php' );
  }

  /**
	 * Register the menu link
	 *
	 * @since    4.0.0
	 */
  public function register_menu_page() {
    add_submenu_page(
			'options-general.php',
      'Podlove Web Player Configuration',
      'Podlove Web Player',
      'manage_options',
      $this->plugin_name . '-settings',
      array( $this, 'page_settings' )
		);
  }

  /**
	 * Register api routes
	 *
	 * @since    4.0.0
	 */
  public function add_routes() {
    $this->api->registerRoutes();
  }
}
