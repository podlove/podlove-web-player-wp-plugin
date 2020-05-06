<?php
/**
 * Player Interoperability
 *
 * Defines methods that can be used to communicate with other plugins
 *
 * @since      5.0.6
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Interoperability {
	/**
	 * The ID of this plugin.
	 *
	 * @since    5.0.6
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
  private $plugin_name;

  public function __construct( $plugin_name ) {
    $this->plugin_name = $plugin_name;
  }

  public function isNetworkActivated() {
    if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
      require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
    }

    return is_plugin_active_for_network( 'podlove-web-player/podlove-web-player.php' );
  }

  public function isPublisherActive() {
    return function_exists('podlove_pwp5_attributes');
  }

  public function isPlayerActiveInPublisher() {
    if (!function_exists('\Podlove\get_webplayer_settings')) {
      return false;
    }

    $settings = (array) \Podlove\get_webplayer_settings();

    return $settings['version'] == 'player_v5';
  }
}
