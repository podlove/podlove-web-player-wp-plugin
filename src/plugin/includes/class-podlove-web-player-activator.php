<?php
require_once plugin_dir_path( __FILE__ ) . '/class-podlove-web-player-options.php';

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Activator {

	/**
	 * Podlove Web Player activator
	 *
	 * @since    5.0.2
	 */
	public static function activate($plugin_name, $networkActivated) {
    $options = new Podlove_Web_Player_Options($plugin_name);
    $options->create($networkActivated);
	}

}
