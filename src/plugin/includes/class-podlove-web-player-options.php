<?php

/**
 * Modify podlove web player settings
 *
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Options
{

  /**
   * The unique identifier of this plugin.
   *
   * @since    5.0.2
   * @access   protected
   * @var      string    $plugin_name    The string used to uniquely identify this plugin.
   */
  protected $plugin_name;

  /**
   * The plugin directory
   *
   * @since    5.0.2
   * @access   protected
   * @var      string    $plugin_directory    The plugin directory.
   */
  protected $plugin_directory;

  /**
   * The default web player configuration
   *
   * @since    5.0.2
   * @access   protected
   * @var      array    $defaults        Default configuration values.
   */
  protected $defaults;


  /**
   * Interoperability Object
   *
   * @since    5.0.13
   * @access   private
   * @var      object   $interoperability   The player interoperability.
   */
  private $interoperability;

  public function __construct($plugin_name)
  {
    global $content_width;

    $this->plugin_name = $plugin_name;
    $this->plugin_directory = plugin_dir_path(__DIR__);
    $this->interoperability = new Podlove_Web_Player_Interoperability($this->plugin_name);

    $this->defaults = array(
      'configs' => array(
        'default' => $this->readFile($this->plugin_directory . 'defaults/configs/default.json', 'json'),
      ),
      'themes' => array(
        'default' => $this->readFile($this->plugin_directory . 'defaults/themes/default.json', 'json'),
      ),
      'templates' => array(
        'default' => $this->readFile($this->plugin_directory . 'defaults/templates/default.html', 'html'),
      ),
      'settings' => array(
        'source' => array(
          'selected' => 'local',
          'items' => array(
            'local' => PODLOVE_WEB_PLAYER_PATH . 'web-player/',
            'cdn' => 'https://cdn.podlove.org/web-player/5.x/'
          )
        ),
        'contentWidth' => $content_width,
        'enclosure' => 'bottom',
        'legacy' => false,
        'defaults' => array(
          'theme' => 'default',
          'config' => 'default',
          'template' => 'default'
        )
      )
    );
  }

  /**
   * Serializes values with defaults
   *
   * @since     5.0.2
   */
  private function serializer($value = [])
  {
    $merged = (object) array_merge((array) $this->defaults, (array) $value);

    return json_encode($merged);
  }

  /**
   * Reads file from plugin
   */
  private function readFile($path = null,  $type = 'json')
  {
    if (!$path) {
      return null;
    }

    $content = file_get_contents($path);

    if ($type === 'json') {
      $content = json_decode($content, true);
    }

    return $content;
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

      $contents[$info['filename']] = $this->readFile(trailingslashit($path) . $file, $info['extension']);
    }

    return $contents;
  }

  /**
   * Creates the plugin options
   *
   * @since     5.0.2
   */
  public function create($networkActivated)
  {
    if ($networkActivated) {
      add_site_option($this->plugin_name, $this->serializer());
    } else {
      add_option($this->plugin_name, $this->serializer());
    }
  }

  /**
   * Gets the defaults
   *
   * @since     5.0.14
   */
  public function presets()
  {
    return array(
      'configs' => $this->readFolder($this->plugin_directory . 'defaults/configs/', 'json'),
      'themes' => $this->readFolder($this->plugin_directory . 'defaults/themes/', 'json'),
      'templates' => $this->readFolder($this->plugin_directory . 'defaults/templates/', 'html')
    );
  }

  /**
   * Reads the plugin options
   *
   * @since     5.0.2
   */
  public function read()
  {
    if ($this->interoperability->isNetworkActivated()) {
      $options = json_decode(get_site_option($this->plugin_name), true);
    } else {
      $options = json_decode(get_option($this->plugin_name), true);
    }

    return array_replace_recursive($this->defaults, $options);
  }

  /**
   * Updates the plugin options
   *
   * @since     5.0.2
   */
  public function update($value = array())
  {
    if ($this->interoperability->isNetworkActivated()) {
      update_site_option($this->plugin_name, $this->serializer($value));
    } else {
      update_option($this->plugin_name, $this->serializer($value));
    }
  }

  /**
   * Updates the plugin options
   *
   * @since     5.0.2
   */
  public function delete()
  {
    if ($this->interoperability->isNetworkActivated()) {
      delete_site_option($this->plugin_name);
    } else {
      delete_option($this->plugin_name);
    }
  }
}
