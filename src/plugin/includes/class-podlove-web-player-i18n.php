<?php
/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    5.0.2
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'podlove-web-player',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

  }

  public static function i18n($translation = '') {
    return __( $translation, 'podlove-web-player' );
  }

  public static function translations() {
    return array(
      'title' => static::i18n( 'Podlove Web Player Configurator' ),

      'config' => array(
        'title' => static::i18n( 'Config <%= id %>' ),
        'active-tab' => static::i18n( 'Active Tab' ),
        'select-tab' => static::i18n( 'Select Tab' ),
        'no-default-tab' => static::i18n( 'none' ),
        'share-tab' => static::i18n( 'Share Tab' ),
        'channels' => static::i18n( 'Channels' ),
        'channels-add' => static::i18n( 'Add' ),
        'share-options' => static::i18n( 'Options' ),
        'share-playtime' => static::i18n( 'Share Playtime' ),
        'embed-player' => static::i18n( 'Embed Player' ),
        'subscribe-button' => static::i18n( 'Subscribe Button' ),
        'feed' => static::i18n( 'Feed URL' ),
        'rss-feed' => static::i18n( 'RSS Feed' ),
        'clients' => static::i18n( 'Clients' ),
        'client-add' => static::i18n( 'Add' ),
        'client-supported-plattforms' => static::i18n( 'Supported Platforms' ),
        'client-service-id' => static::i18n( 'Service Id' ),

        'create' => static::i18n( 'Add Config' ),
        'create-message' => static::i18n( 'Please set a config id' ),
        'delete' => static::i18n( 'Delete Config' ),
        'delete-message' => static::i18n( 'Do you really want to delete the config <%= id %>' )
      ),

      'template' => array(
        'title' => static::i18n( 'Config <%= id %>' ),
        'markup' => static::i18n( 'Markup' ),

        'create' => static::i18n( 'Add Template' ),
        'create-message' => static::i18n( 'Please set a template id' ),
        'delete' => static::i18n( 'Delete Template' ),
        'delete-message' => static::i18n( 'Do you really want to delete the template <%= id %>' )
      ),

      'theme' => array(
        'title' => static::i18n( 'Theme <%= id %>' ),
        'colors' => static::i18n( 'Colors' ),
        'color-brand' => static::i18n( 'Brand' ),
        'color-brand-dark' => static::i18n( 'Brand Dark' ),
        'color-brand-darkest' => static::i18n( 'Brand Darkest' ),
        'color-brand-lightest' => static::i18n( 'Brand Lightest' ),
        'color-shade-base' => static::i18n( 'Shade Base' ),
        'color-shade-dark' => static::i18n( 'Shade Dark' ),
        'color-contrast' => static::i18n( 'Contrast' ),
        'color-alt' => static::i18n( 'Alt' ),
        'fonts' => static::i18n( 'Fonts' ),
        'font-sources' => static::i18n( 'Font Sources' ),
        'font-add' => static::i18n( 'Add Source' ),
        'font-weight' => static::i18n( 'Font Weight' ),
        'font-family' => static::i18n( 'Font Family' ),

        'create' => static::i18n( 'Add Theme' ),
        'create-message' => static::i18n( 'Please set a theme id' ),
        'delete' => static::i18n( 'Delete Theme' ),
        'delete-message' => static::i18n( 'Do you really want to delete the theme <%= id %>' )
      ),

      'settings' => array (
        'title' => static::i18n( 'Settings' ),
        'source' => static::i18n( 'Source' ),
        'select-source' => static::i18n( 'Select Source' ),
        'enclosure' => static::i18n( 'Transform Post Enclosure' ),
        'enclosure-disabled' => static::i18n( 'disable automatic enclosure' ),
        'enclosure-top' => static::i18n( 'insert player at the top of the post' ),
        'enclosure-bottom' => static::i18n( 'insert player at the end of the post' ),
        'legacy' => static::i18n( 'Legacy' ),
        'legacy-browser' => static::i18n( 'enable support for legacy browsers (increases loading time)' ),
        'defaults' => static::i18n( 'Default Appearance' ),
        'default-config' => static::i18n( 'Default Configuration' ),
        'default-theme' => static::i18n( 'Default Theme' ),
        'default-template' => static::i18n( 'Default Template' )
      ),

      'navigation' => array(
        'configuration' =>  static::i18n( 'Configuration' ),
        'themes' => static::i18n( 'Themes' ),
        'templates' => static::i18n( 'Templates' ),
        'settings' => static::i18n( 'Settings' )
      ),

      'preview' => array(
        'config' => static::i18n( 'Config' ),
        'config-placeholder' => static::i18n( 'Select Config' ),
        'theme' => static::i18n( 'Theme' ),
        'theme-placeholder' => static::i18n( 'Select Theme' ),
        'template' => static::i18n( 'Template' ),
        'template-placeholder' => static::i18n( 'Select Template' ),
        'size' => static::i18n( 'Size' ),
        'size-placeholder' => static::i18n( 'Select Size' ),
      ),

      'modal' => array(
        'id-invalid' => static::i18n( 'Only lower cased characters are allowed' ),
        'id-exists' => static::i18n( 'Id already exists' ),
        'id-assigned' => static::i18n( 'You can\'t delete "<%= id %>" since it is assigned as the default for "<%= type %>"' ),
        'delete' => static::i18n( 'Delete' ),
        'cancel' => static::i18n( 'Cancel' ),
      ),

      'actions' => array(
        'add' => 'Add',
        'save' => 'Save',
        'delete' => 'Delete'
      )
    );
  }
}
