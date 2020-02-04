<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://docs.podlove.org/podlove-web-player/
 * @since             1.0.0
 * @package           Podlove_Web_Player
 *
 * @wordpress-plugin
 * Plugin Name:       Podlove Web Player Beta
 * Plugin URI:        https://docs.podlove.org/podlove-web-player/
 * Description:       Audio First Podcast Web Player
 * Version:           4.0.0
 * Author:            Alexander Heimbuch
 * Author URI:        http://alexander.heimbu.ch
 * License:           MIT
 * License URI:       https://opensource.org/licenses/MIT
 * Text Domain:       podlove-web-player
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'PODLOVE_WEB_PLAYER_VERSION', '4.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-podlove-web-player-activator.php
 */
function activate_podlove_web_player() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-podlove-web-player-activator.php';
	Podlove_Web_Player_Activator::activate('podlove-web-player');
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-podlove-web-player-deactivator.php
 */
function deactivate_podlove_web_player() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-podlove-web-player-deactivator.php';
	Podlove_Web_Player_Deactivator::deactivate('podlove-web-player');
}

register_activation_hook( __FILE__, 'activate_podlove_web_player' );
register_deactivation_hook( __FILE__, 'deactivate_podlove_web_player' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-podlove-web-player.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    5.0.0
 */
function run_podlove_web_player() {
	$plugin = new Podlove_Web_Player();
	$plugin->run();
}

run_podlove_web_player();
