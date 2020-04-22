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

  public function isPublisherActive() {
    return function_exists('podlove_pwp5_attributes');
  }
}
