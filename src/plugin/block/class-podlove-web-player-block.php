<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      5.0.2
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
class Podlove_Web_Player_Block {

	/**
	 * The ID of this plugin.
	 *
	 * @since    5.0.2
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    5.0.2
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
   * Plugin api
   */
  private $api;

  /**
   * Plugin api
   */
  private $embed;

  /**
   * Plugin shortcode
   */
  private $shortcode;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    5.0.2
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
    $this->version = $version;
    $this->shortcode = new Podlove_Web_Player_Shortcode( $this->plugin_name, $this->version );
    $this->api = new Podlove_Web_Player_Admin_API( $this->plugin_name, $this->version );
    $this->embed = new Podlove_Web_Player_Embed_API( $this->plugin_name, $this->version );
  }

  public function enqueue_scripts() {
    wp_enqueue_script( $this->plugin_name . '-block', plugin_dir_url( __FILE__ ) . '/js/block.js', array( 'wp-blocks', 'wp-i18n', 'wp-element' ), $this->version, true );
    wp_localize_script( $this->plugin_name . '-block', 'PODLOVE_WEB_PLAYER', array(
      'api' => $this->api->routes(),
      'embed' => $this->embed->routes(),
      'nonce' => wp_create_nonce('wp_rest')
    )
  );
  }

  public function register_block() {
    if ( ! function_exists( 'register_block_type' ) ) {
      return;
    }

    register_block_type( 'podlove-web-player/shortcode', array(
      'render_callback' => array($this->shortcode, 'render')
    ));
  }
}
