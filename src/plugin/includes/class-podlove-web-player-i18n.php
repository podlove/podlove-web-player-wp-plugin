<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    4.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'podlove-web-player',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

  }

  public static function translations() {
    return array(
      'title' => __( 'Podlove Web Player Configurator' , 'podlove-web-player' ),
      'show_poster' => __( 'Show Poster' , 'podlove-web-player' ),

      'select' => __( 'Select' , 'podlove-web-player' ),
      'save' => __( 'Save' , 'podlove-web-player' ),
      'default' => __( 'Default' , 'podlove-web-player' ),

      'message_initializing' => __( 'Initializing' , 'podlove-web-player' ),
      'message_saving' => __( 'Saving' , 'podlove-web-player' ),
      'message_creating' => _( 'Creating', 'podlove-web-player' ),

      'config' => __( 'Config' , 'podlove-web-player' ),
      'error_load_config' => __( 'Error Loading Config' , 'podlove-web-player' ),
      'error_save_config' => __( 'Error Saving Config' , 'podlove-web-player' ),
      'error_remove_config' => __( 'Error Removing Config' , 'podlove-web-player' ),

      'controls' => __( 'Controls' , 'podlove-web-player' ),
      'components' => __( 'Components' , 'podlove-web-player' ),

      'theme' => __( 'Theme' , 'podlove-web-player' ),
      'error_save_theme' => __( 'Error Saving Theme', 'podlove-web-player' ),
      'error_remove_theme' => __( 'Error Removing Theme' , 'podlove-web-player' ),

      'template' => __( 'Template', 'podlove-web-player' ),
      'error_save_template' => __( 'Error Saving Template', 'podlove-web-player' ),
      'error_remove_template' => __( 'Error Removing Template' , 'podlove-web-player' ),

      'preview' => __( 'Preview' , 'podlove-web-player' ),

      'enclosures' => __( 'WordPress "enclosures"' , 'podlove-web-player' ),
      'enclosures_tooltip' => __( 'WordPress automatically creates an "enclosure" custom field whenever it detects an URL to a media fiel in the post text. Use this option to turn these enclosures into Podlove Web Player instances' , 'podlove-web-player' ),
      'enclosures_enabled' => __( 'Turn enclosures to players', 'podlove-web-player' ),
      'enclosures_bottom' => __( 'Put player to bottom of post', 'podlove-web-player' ),

      'error_save_settings' => __( 'Error Saving Settings', 'podlove-web-player' )
    );
  }
}
