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

    /***
     * static configuration values
     * @since    5.1.3
     * @access   private
     * @var      object   $interoperability   The player interoperability.
     */
    protected $config;

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
                ),

                'enclosure' => 'bottom',
                'legacy' => false,
                'defaults' => array(
                    'theme' => 'default',
                    'config' => 'default',
                    'template' => 'default',
                ),
            ),
        );

        $this->config = array(
            'settings' => array(
                'source' => array(
                    'items' => array(
                        'local' => PODLOVE_WEB_PLAYER_PATH . '/web-player/',
                        'cdn' => 'https://cdn.podlove.org/web-player/5.x/',
                    ),
                ),
                'contentWidth' => $content_width,
            ),
        );
    }

    /**
     * Serializes values with defaults
     *
     * @since     5.0.2
     */
    private function serializer($value = [])
    {
        return json_encode($value);
    }

    /**
     * Reads file from plugin
     */
    private function readFile($path = null, $type = 'json')
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
            'templates' => $this->readFolder($this->plugin_directory . 'defaults/templates/', 'html'),
        );
    }
/**
     * Creates a fallback to defaults
     *
     * @since 5.2.6
     */
    private function fallback($type, $payload)
    {
        switch ($type) {
            case 'config':
                return array(
                    'activeTab' => isset($payload['activeTab']) ? $payload['activeTab'] : 'chapters',
                    'subscribe-button' => array(
                        'feed' => isset($payload['subscribe-button']['feed']) ? $payload['subscribe-button']['feed'] : null,
                        'clients' => isset($payload['subscribe-button']['clients']) ? $payload['subscribe-button']['clients'] : []
                    ),
                    'share' => array(
                        'channels' => isset($payload['share']['channels']) ? $payload['share']['channels'] : [],
                        'outlet' => '/share.html',
                        'sharePlaytime' => isset($payload['share']['sharePlaytime']) ? $payload['share']['sharePlaytime'] : true
                    ),
                );

            case 'theme':
              return array(
                'tokens' => array(
                  'brand' => isset($payload['tokens']['brand']) ? $payload['tokens']['brand'] : '#E64415',
                  'brandDark' => isset($payload['tokens']['brandDark']) ? $payload['tokens']['brandDark'] : '#235973',
                  'brandDarkest' => isset($payload['tokens']['brandDarkest']) ? $payload['tokens']['brandDarkest'] : '#1A3A4A',
                  'brandLightest' => isset($payload['tokens']['brandLightest']) ? $payload['tokens']['brandLightest'] : '#E9F1F5',
                  'shadeDark' => isset($payload['tokens']['shadeDark']) ? $payload['tokens']['shadeDark'] : '#807E7C',
                  'shadeBase' => isset($payload['tokens']['shadeBase']) ? $payload['tokens']['shadeBase'] : '#807E7C',
                  'contrast' => isset($payload['tokens']['contrast']) ? $payload['tokens']['contrast'] : '#000',
                  'alt' => isset($payload['tokens']['alt']) ? $payload['tokens']['alt'] : '#fff'
                ),
                'fonts' => array(
                  'ci' => array(
                    'name' =>  isset($payload['fonts']['ci']['name']) ? $payload['fonts']['ci']['name'] : 'ci',
                    'family' =>  isset($payload['fonts']['ci']['family']) ? $payload['fonts']['ci']['family'] : [],
                    'src' =>  isset($payload['fonts']['ci']['src']) ? $payload['fonts']['ci']['src'] : [],
                    'weight' =>  isset($payload['fonts']['ci']['weight']) ? $payload['fonts']['ci']['weight'] : 800
                  ),
                  'bold' => array(
                    'name' =>  isset($payload['fonts']['bold']['name']) ? $payload['fonts']['bold']['name'] : 'bold',
                    'family' =>  isset($payload['fonts']['bold']['family']) ? $payload['fonts']['bold']['family'] : [],
                    'src' =>  isset($payload['fonts']['bold']['src']) ? $payload['fonts']['bold']['src'] : [],
                    'weight' =>  isset($payload['fonts']['bold']['weight']) ? $payload['fonts']['bold']['weight'] : 700
                  ),
                  'regular' => array(
                    'name' =>  isset($payload['fonts']['regular']['name']) ? $payload['fonts']['regular']['name'] : 'regular',
                    'family' =>  isset($payload['fonts']['regular']['family']) ? $payload['fonts']['regular']['family'] : [],
                    'src' =>  isset($payload['fonts']['regular']['src']) ? $payload['fonts']['regular']['src'] : [],
                    'weight' =>  isset($payload['fonts']['regular']['weight']) ? $payload['fonts']['regular']['weight'] : 300
                  )
                )
              );

            case 'template':
              return isset($payload) ? $payload : '';

            default:
                return array();
        }
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

        $settings = array_replace_recursive($options ?? array(), $this->config);

        // configs
        foreach($settings['configs'] as $key => $value) {
          $settings['configs'][$key] = $this->fallback('config', $value);
        }

        // themes
        foreach($settings['themes'] as $key => $value) {
          $settings['themes'][$key] = $this->fallback('theme', $value);
        }

        // templates
        foreach($settings['templates'] as $key => $value) {
          $settings['templates'][$key] = $this->fallback('template', $value);
        }

        return $settings;
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
