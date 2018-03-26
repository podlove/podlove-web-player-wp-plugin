<?php
require_once plugin_dir_path( __FILE__ ) . '/class-podlove-web-player-options.php';

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Activator {

	/**
	 * Podlove Web Player activator
	 *
	 * @since    4.0.0
	 */
	public static function activate($plugin_name) {
    $options = new Podlove_Web_Player_Options($plugin_name);
    $options->create();
	}

}
