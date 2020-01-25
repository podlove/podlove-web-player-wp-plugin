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
		wp_enqueue_script( $this->plugin_name . '-configurator', plugin_dir_url( __FILE__ ) . 'js/app.js', array(), $this->version, true );

    wp_localize_script( $this->plugin_name . '-configurator', 'PODLOVE', array(
        'i18n' => Podlove_Web_Player_i18n::translations(),
        'api' => array(
          'bootstrap' => esc_url_raw( rest_url( $this->plugin_name . '/' . $this->version . '/' . 'bootstrap' ) ),
          'config' => esc_url_raw( rest_url( $this->plugin_name . '/' . $this->version . '/' . 'config' ) ),
          'theme' => esc_url_raw( rest_url( $this->plugin_name . '/' . $this->version . '/' . 'theme' ) ),
          'template' => esc_url_raw( rest_url( $this->plugin_name . '/' . $this->version . '/' . 'template' ) ),
          'settings' => esc_url_raw( rest_url( $this->plugin_name . '/' . $this->version . '/' . 'settings' ) )
        )
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
    register_rest_route( $this->plugin_name . '/' . $this->version, 'bootstrap',
      array(
        'methods' => 'GET',
        'callback' => array( $this, 'api_load_config' ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'config/(?P<id>\w+)',
      array(
        'methods' => 'POST',
        'callback' => array( $this, 'api_save_config' ),
        'args' => array (
          'id' => array(
            'required' => true
          ),
          'activeTab' => array(
            'required' => true
          ),
          'subscribe-button' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'config/subscribe-button', $param );
            }
          ),
          'share' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'config/share', $param );
            }
          )
        ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'theme/(?P<id>\w+)',
      array(
        'methods' => 'POST',
        'callback' => array( $this, 'api_save_theme' ),
        'args' => array (
          'id' => array(
            'required' => true
          ),
          'tokens' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'theme/tokens', $param );
            }
          ),
          'fonts' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'theme/fonts', $param );
            }
          )
        ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'template/(?P<id>\w+)',
      array(
        'methods' => 'POST',
        'callback' => array( $this, 'api_save_template' ),
        'args' => array (
          'id' => array(
            'required' => true
          ),
          'template' => array(
            'required' => true
          )
          ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'config/(?P<id>\w+)',
      array(
        'methods' => 'DELETE',
        'callback' => array( $this, 'api_delete_config' ),
        'args' => array (
          'id' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'delete/id', $param );
            }
          )
        ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'theme/(?P<id>\w+)',
      array(
        'methods' => 'DELETE',
        'callback' => array( $this, 'api_delete_theme' ),
        'args' => array (
          'id' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'delete/id', $param );
            }
          )
        ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'template/(?P<id>\w+)',
      array(
        'methods' => 'DELETE',
        'callback' => array( $this, 'api_delete_config' ),
        'args' => array (
          'id' => array(
            'required' => true,
            'validate_callback' => function( $param ) {
              return $this->options->validate( 'delete/id', $param );
            }
          )
        ),
        'permissions_callback' => array( $this, 'api_permissions' )
      )
    );

    register_rest_route( $this->plugin_name . '/' . $this->version, 'settings',
      array(
        'methods' => 'POST',
        'callback' => array( $this, 'api_save_settings' ),
        'args' => array (
          'source' => array(
            'required' => true
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
    $options = $this->options->read();
    $configId = $request->get_param( 'id' );

    $options['configs'] = array_merge($options['configs'], array(
      $configId => array(
        'activeTab' => $request->get_param( 'activeTab' ),
        'subscribe-button' => $request->get_param( 'subscribe-button' ),
        'share' => $request->get_param( 'share' )
      )
    ));


    $this->options->update($options);
    $options = $this->options->read();

    return rest_ensure_response( $options['configs'][$configId] );
  }

  /**
	 * Save API theme
	 *
	 * @since    4.0.0
	 */
  public function api_save_theme( WP_REST_Request $request ) {
    $options = $this->options->read();
    $themeId = $request->get_param( 'id' );

    $options['themes'] = array_merge($options['themes'], array(
      $themeId => array(
        'tokens' => $request->get_param( 'tokens' ),
        'fonts' => $request->get_param( 'fonts' )
      )
    ));

    $this->options->update($options);
    $options = $this->options->read();

    return rest_ensure_response( $options['themes'][$themeId] );
  }

  /**
	 * Save API template
	 *
	 * @since    4.0.0
	 */
  public function api_save_template( WP_REST_Request $request ) {
    $options = $this->options->read();
    $templateId = $request->get_param( 'id' );

    $options['templates'] = array_merge($options['templates'], array(
      $templateId => $request->get_param( 'template' )
    ));


    $this->options->update($options);
    $options = $this->options->read();

    return rest_ensure_response( $options['templates'][$templateId] );
  }

  /**
	 * Delete API config
	 *
	 * @since    4.0.0
	 */
  public function api_delete_config( WP_REST_Request $request ) {
    $options = $this->options->read();
    $configId = $request->get_param( 'id' );

    unset($options['configs'][$configId]);

    $this->options->update($options);
    return rest_ensure_response( true );
  }

  /**
	 * Delete API theme
	 *
	 * @since    4.0.0
	 */
  public function api_delete_theme( WP_REST_Request $request ) {
    $options = $this->options->read();
    $themeId = $request->get_param( 'id' );

    unset($options['themes'][$themeId]);

    $this->options->update($options);
    return rest_ensure_response( true );
  }

  /**
	 * Delete API template
	 *
	 * @since    4.0.0
	 */
  public function api_delete_template( WP_REST_Request $request ) {
    $options = $this->options->read();
    $templateId = $request->get_param( 'id' );

    unset($options['templates'][$templateId]);

    $this->options->update($options);
    return rest_ensure_response( true );
  }

  /**
	 * Delete API template
	 *
	 * @since    4.0.0
	 */
  public function api_save_settings( WP_REST_Request $request ) {
    $options = $this->options->read();
    $source = $request->get_param( 'source' );

    $options['settings']['source'] = $source;

    $this->options->update($options);
    $options = $this->options->read();

    return rest_ensure_response( $options['settings'] );
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
