<?php
require_once plugin_dir_path( __FILE__ ) . '/class-podlove-web-player-options.php';

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Deactivator {

	public static function deactivate($plugin_name) {
    $options = new Podlove_Web_Player_Options($plugin_name);
    $options->delete();
	}

}
