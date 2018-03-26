<?php

/**
 * Modify podlove web player settings
 *
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <podlove@heimbu.ch>
 */
class Podlove_Web_Player_Options {

  /**
	 * The unique identifier of this plugin.
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

  /**
	 * The default web player configuration
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      array    $defaults        Default configuration values.
	 */
  protected $defaults;

	public function __construct( $plugin_name ) {

		$this->plugin_name = $plugin_name;
		$this->defaults = array(
      'show' => array(
        'poster' => null
      ),
      'theme' => array(
        'main' => '#2B8AC6',
        'highlight' => null
      ),
      'tabs' => array(
        'info'      => false,
        'chapters'  => false,
        'share'     => false,
        'download'  => false,
        'audio'     => false
      ),
      'visibleComponents' => array(
        'tabChapters',
        'tabDownload',
        'tabAudio',
        'tabShare',
        'poster',
        'tabInfo',
        'showTitle',
        'episodeTitle',
        'subtitle',
        'progressbar',
        'controlSteppers',
        'controlChapters'
      ),
      'enclosure' => array(
        'enabled' => false,
        'bottom'  => false
      )
    );
	}

    /**
	 * Serializes values with defaults
	 *
	 * @since     1.0.0
	 */
  private function serializer($value = []) {
    $merged = (object) array_merge((array) $this->defaults, (array) $value);

    return json_encode($merged);
  }

  /**
	 * Creates the plugin options
	 *
	 * @since     1.0.0
	 */
	public function create() {
    add_option( $this->plugin_name, $this->serializer() );
  }

  /**
	 * Reads the plugin options
	 *
	 * @since     1.0.0
	 */
	public function read() {
    $value = get_option( $this->plugin_name, json_encode($this->defaults) );

    return json_decode( $value, true );
  }

  /**
	 * Updates the plugin options
	 *
	 * @since     1.0.0
	 */
  public function update($value = []) {
    update_option( $this->plugin_name, $this->serializer($value) );
  }

  /**
	 * Updates the plugin options
	 *
	 * @since     1.0.0
	 */
  public function delete() {
    delete_option( $this->plugin_name );
  }

  /**
   * Validates the plugin options
   */
  public function validate($type, $value) {
    switch ($type) {
      case 'theme':
        return array_key_exists('main', $value) && array_key_exists('highlight', $value);

      case 'tabs':
        return
          array_key_exists('info', $value) &&
          array_key_exists('chapters', $value) &&
          array_key_exists('share', $value) &&
          array_key_exists('download', $value) &&
          array_key_exists('audio', $value);

      case 'visibleComponents':
        return is_array($value);

      case 'enclosure':
        return array_key_exists('enabled', $value) && array_key_exists('bottom', $value);

      case 'show':
        return array_key_exists('poster', $value);

      default:
        return false;
    }
  }
}
