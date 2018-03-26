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
   * Plugin options
   */
  private $options;

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
    $this->options = new Podlove_Web_Player_Options($plugin_name);
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
    wp_enqueue_script( $this->plugin_name . '-player', 'https://cdn.podlove.org/web-player/embed.js', array(), $this->version, true );
		wp_enqueue_script( $this->plugin_name . '-configurator', plugin_dir_url( __FILE__ ) . 'js/app.js', array(), $this->version, true );

    wp_localize_script( $this->plugin_name . '-configurator', 'PODLOVE', array(
        'i18n' => Podlove_Web_Player_i18n::translations(),
        'api' => esc_url_raw( rest_url( $this->plugin_name . '/' . $this->version . '/' . 'config' ) )
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
    register_rest_route( $this->plugin_name . '/' . $this->version, 'config',
      array(
        'methods' => 'GET',
        'callback' => array( $this, 'api_load_config' ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'config',
      array(
        'methods' => 'POST',
        'callback' => array( $this, 'api_save_config' ),
        'args' => array (
          'theme' => array(
            'required' => true,
            'validate_callback' => function( $param, $request, $key ) {
              return $this->options->validate( 'theme', $param );
            }
          ),
          'tabs' => array(
            'required' => true,
            'validate_callback' => function( $param, $request, $key ) {
              return $this->options->validate( 'tabs', $param );
            }
          ),
          'visibleComponents' => array(
            'required' => true,
            'validate_callback' => function( $param, $request, $key ) {
              return $this->options->validate( 'visibleComponents', $param );
            }
          ),
          'enclosure' => array(
            'required' => true,
            'validate_callback' => function( $param, $request, $key ) {
              return $this->options->validate( 'enclosure', $param );
            }
          ),
          'show' => array(
            'required' => true,
            'validate_callback' => function( $param, $request, $key ) {
              return $this->options->validate( 'show', $param );
            }
          )
        ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );
  }

  /**
	 * Check API permissions
	 *
	 * @since    4.0.0
	 */
  public function api_permissions() {
		return current_user_can( 'manage_options' );
  }

  /**
	 * Save API config
	 *
	 * @since    4.0.0
	 */
  public function api_save_config( WP_REST_Request $request ) {
    $config = array(
			'theme' => $request->get_param( 'theme' ),
			'tabs' => $request->get_param( 'tabs' ),
      'visibleComponents' => $request->get_param( 'visibleComponents' ),
      'enclosure' => $request->get_param( 'enclosure' ),
      'show' => $request->get_param( 'show' )
    );

    $this->options->update( $config );

    return rest_ensure_response( $this->options->read() );
  }

  /**
	 * Load API config
	 *
	 * @since    4.0.0
	 */
  public function api_load_config() {
    return rest_ensure_response( $this->options->read() );
  }
}
