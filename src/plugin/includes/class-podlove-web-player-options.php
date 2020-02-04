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
class Podlove_Web_Player_Options
{

  /**
   * The unique identifier of this plugin.
   *
   * @since    5.0.0
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The plugin directory
   *
   * @since    5.0.0
   * @access   protected
   * @var      string    $plugin_directory    The plugin directory.
   */
  protected $plugin_directory;

  /**
   * The default web player configuration
   *
   * @since    5.0.0
   * @access   protected
   * @var      array    $defaults        Default configuration values.
   */
  protected $defaults;

  public function __construct($plugin_name)
  {

    $this->plugin_name = $plugin_name;
    $this->plugin_directory = plugin_dir_path(__DIR__);
    $this->defaults = array(
      'configs' => $this->readFolder($this->plugin_directory . 'defaults/configs/', 'json'),
      'themes' => $this->readFolder($this->plugin_directory . 'defaults/themes/', 'json'),
      'templates' => $this->readFolder($this->plugin_directory . 'defaults/templates/', 'html'),
      'settings' => array(
        'source' => array(
          'selected' => 'cdn',
          'items' => array(
            'cdn' => 'https://cdn.podlove.org/web-player/5.x/embed.js',
            'local' => '/wp-content/plugins/podlove-web-player-beta/web-player/embed.js'
          )
        ),
        'enclosure' => 'bottom'
      )
    );
  }

  /**
   * Serializes values with defaults
   *
   * @since     1.0.0
   */
  private function serializer($value = [])
  {
    $merged = (object) array_merge((array) $this->defaults, (array) $value);

    return json_encode($merged);
  }

  /**
   * Reads files from the plugin
   */
  private function readFolder($path = null, $type = 'json')
  {
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

      $contents[$info['filename']] = file_get_contents(trailingslashit($path) . $file);

      if ($info['extension'] === 'json') {
        $contents[$info['filename']] = json_decode($contents[$info['filename']], true);
      }
    }

    return $contents;
  }

  /**
   * Creates the plugin options
   *
   * @since     1.0.0
   */
  public function create()
  {
    add_option($this->plugin_name, $this->serializer());
  }

  /**
   * Reads the plugin options
   *
   * @since     1.0.0
   */
  public function read()
  {
    $options = get_option($this->plugin_name);
    return json_decode($options, true);
  }

  /**
   * Updates the plugin options
   *
   * @since     1.0.0
   */
  public function update($value = array())
  {
    update_option($this->plugin_name, $this->serializer($value));
  }

  /**
   * Updates the plugin options
   *
   * @since     1.0.0
   */
  public function delete()
  {
    delete_option($this->plugin_name);
  }
}
