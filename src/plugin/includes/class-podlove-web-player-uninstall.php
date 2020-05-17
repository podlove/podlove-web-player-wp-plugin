<?php
require_once plugin_dir_path( __FILE__ ) . '/class-podlove-web-player-interoperability.php';
require_once plugin_dir_path( __FILE__ ) . '/class-podlove-web-player-options.php';

/**
 * Fired during plugin uninstall.
 *
 * This class defines all code necessary to run during the plugin's uninstall.
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Uninstall {

	public static function uninstall($plugin_name) {
    $options = new Podlove_Web_Player_Options($plugin_name);
    $options->delete();
	}

}
