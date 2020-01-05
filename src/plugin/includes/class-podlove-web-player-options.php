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
	 * The plugin directory
	 *
	 * @since    4.0.0
	 * @access   protected
	 * @var      string    $plugin_directory    The plugin directory.
	 */
  protected $plugin_directory;

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
		$this->plugin_directory = plugin_dir_path( __DIR__ );
		$this->defaults = array(
      'configs' => $this->readFolder( $this->plugin_directory . 'defaults/configs/' , 'json'),
      'themes' => $this->readFolder( $this->plugin_directory . 'defaults/themes/' , 'json'),
      'templates' => $this->readFolder( $this->plugin_directory . 'defaults/templates/' , 'html'),
      'settings' => array('version' => 'local')
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
   * Reads files from the plugin
   */
  private function readFolder($path = null, $type = 'json') {
    if (!$path || !is_dir($path)) {
      return null;
    }

    $files = scandir($path);
    $contents = array();

    foreach ($files as &$file) {
      $info = pathinfo($file);

      if ($info['extension'] !== $type) {
        continue;
      }

      $contents[$info['filename']] = file_get_contents( trailingslashit($path) . $file );

      if ($info['extension'] === 'json') {
        $contents[$info['filename']] = json_decode( $contents[$info['filename']], true );
      }
    }

    return $contents;
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
    // $value = get_option( $this->plugin_name, json_encode($this->defaults) );
    return $this->defaults;
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
      case 'configs':
        return array_key_exists('configs', $value) && array_key_exists('default', $value['config']);
      case 'themes':
        return array_key_exists('themes', $value) && array_key_exists('default', $value['themes']);
      case 'templates':
        return array_key_exists('templates', $value);
      case 'settings':
        return array_key_exists('settings', $value) && array_key_exists('version', $value);

      default:
        return false;
    }
  }
}
