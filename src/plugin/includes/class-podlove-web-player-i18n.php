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
        'create-id' => static::i18n( 'Config ID' ),
        'create-blueprint' => static::i18n( 'Config Blueprint' ),
        'delete' => static::i18n( 'Delete Config' ),
        'delete-message' => static::i18n( 'Do you really want to delete the config <%= id %>' ),

        'presets' => array(
          'default' => static::i18n( 'Chapters tab default enabled;Share settings active;Subscribe Button not defined' ),
          'full-clients' => static::i18n( 'No default tab;Share settings active;Subscribe Buttone defined' )
        ),

        'related-episodes' => array(
          'title' => static::i18n( 'Related Episodes' ),
          'description' => static::i18n( 'Related episodes displayed in the player and automatically played ' ),
          'source' => static::i18n( 'Source' ),
          'sources' => array(
            'disabled' => array(
              'title' => static::i18n( 'Disabled' ),
              'description' => static::i18n( 'Don\'t show related episodes' )
            ),
            'show' => array(
              'title' => static::i18n( 'Show' ),
              'label' => static::i18n( 'Select a Show' ),
              'description' => static::i18n( 'Episodes from this show are listed in the related episodes' )
            ),
            'podcast' => array(
              'title' => static::i18n( 'Podcast' ),
              'description' => static::i18n( 'Latest 25 episodes are listed in the related episodes' )
            )
          )
        )
      ),

      'template' => array(
        'title' => static::i18n( 'Config <%= id %>' ),
        'markup' => static::i18n( 'Markup' ),

        'create' => static::i18n( 'Add Template' ),
        'create-id' => static::i18n( 'Template ID' ),
        'create-blueprint' => static::i18n( 'Template Blueprint' ),
        'delete' => static::i18n( 'Delete Template' ),
        'delete-message' => static::i18n( 'Do you really want to delete the template <%= id %>' ),
        'presets' => array(
          'default' => static::i18n( 'Use case: Landing Pages;Full feature play and audio controls;Tabs: chapters, transcripts, files, playlist, share;Subscribe Button' ),
          'compact' => static::i18n( 'Use case: List Pages;Reduced play and audio controls;No tabs;Subscribe Button' ),
          'sidebar' => static::i18n( 'Use case: Sidebar;Reduced play and audio controls;No tabs;Subscribe Button' ),
          'classic' => static::i18n( 'Use case: Landing Pages;Full feature play and audio controls;Tabs: shownotes, chapters, transcripts, files, playlist, share;Subscribe Button' ),
        )
      ),

      'theme' => array(
        'title' => static::i18n( 'Theme <%= id %>' ),
        'colors' => static::i18n( 'Colors' ),
        'color-brand' => static::i18n( 'Brand' ),
        'color-brand-description' => static::i18n( 'Podcast Show Name, Subscribe Button' ),
        'color-brand-dark' => static::i18n( 'Brand Dark' ),
        'color-brand-dark-description' => static::i18n( 'Player Controls, Background Tabs, Social Icons' ),
        'color-brand-darkest' => static::i18n( 'Brand Darkest' ),
        'color-brand-darkest-description' => static::i18n( 'Hover Play Button' ),
        'color-brand-lightest' => static::i18n( 'Brand Lightest' ),
        'color-brand-lightest-description' => static::i18n( 'Background Player' ),
        'color-shade-base' => static::i18n( 'Shade Base' ),
        'color-shade-base-description' => static::i18n( 'Dotted Lines' ),
        'color-shade-dark' => static::i18n( 'Shade Dark' ),
        'color-shade-dark-description' => static::i18n( 'Highlight Transcript' ),
        'color-contrast' => static::i18n( 'Contrast' ),
        'color-contrast-description' => static::i18n( 'Text Player' ),
        'color-alt' => static::i18n( 'Alt' ),
        'color-alt-description' => static::i18n( 'Text Tabs' ),
        'fonts' => static::i18n( 'Fonts' ),
        'font-name' => static::i18n( 'Font Name' ),
        'font-sources' => static::i18n( 'Font Sources' ),
        'font-add' => static::i18n( 'Add Font' ),
        'font-weight' => static::i18n( 'Font Weight' ),
        'font-family' => static::i18n( 'Font Family' ),

        'create' => static::i18n( 'Add Theme' ),
        'create-id' => static::i18n( 'Theme ID' ),
        'create-blueprint' => static::i18n( 'Theme Blueprint' ),
        'delete' => static::i18n( 'Delete Theme' ),
        'delete-message' => static::i18n( 'Do you really want to delete the theme <%= id %>' ),
        'presets' => array(
          'default' => static::i18n( 'Default Web Player Fonts & Colors' ),
          'twentytwenty' => static::i18n( 'Wordpress TwentyTwenty Fonts & Colors' ),
          'twentynineteen' => static::i18n( 'Wordpress TwentyNineteen Fonts & Colors' ),
        )
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
        'defaults' => static::i18n( 'Appearance' ),
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
        'config' => static::i18n( 'Preview Config' ),
        'config-placeholder' => static::i18n( 'Select Config' ),
        'theme' => static::i18n( 'Preview Theme' ),
        'theme-placeholder' => static::i18n( 'Select Theme' ),
        'template' => static::i18n( 'Preview Template' ),
        'template-placeholder' => static::i18n( 'Select Template' ),
        'size' => static::i18n( 'Preview Size' ),
        'size-placeholder' => static::i18n( 'Select Size' ),
      ),

      'modal' => array(
        'id-invalid' => static::i18n( 'Only lower cased characters, numbers and dashes are allowed' ),
        'id-exists' => static::i18n( 'Id already exists' ),
        'id-assigned' => static::i18n( 'You can\'t delete "<%= id %>" since it is assigned as the default for "<%= type %>"' ),
        'delete' => static::i18n( 'Delete' ),
        'cancel' => static::i18n( 'Cancel' ),
        'add' => static::i18n( 'Add' ),
      ),

      'actions' => array(
        'add' => 'Add',
        'save' => 'Save',
        'delete' => 'Delete'
      )
    );
  }
}
