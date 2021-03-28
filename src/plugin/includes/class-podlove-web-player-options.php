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
     * Interoperability Object
     *
     * @since    5.0.13
     * @access   private
     * @var      object   $interoperability   The player interoperability.
     */
    private $interoperability;

    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
        $this->plugin_directory = plugin_dir_path(__DIR__);
        $this->interoperability = new Podlove_Web_Player_Interoperability($this->plugin_name);

        $this->defaultConfig = $this->readFile($this->plugin_directory . 'defaults/configs/default.json', 'json');
        $this->defaultTheme = $this->readFile($this->plugin_directory . 'defaults/themes/default.json', 'json');
        $this->defaultTemplate = $this->readFile($this->plugin_directory . 'defaults/templates/default.html', 'html');
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
     * Ensures defaults are available
     *
     * @since   5.4.0
     */
    private function defaults($type, $collection, $default)
    {
        $result = array();
        $collection = (is_array($collection) ? $collection : array());

        if (!isset($collection['default'])) {
          $collection['default'] = $default;
        }

        foreach ($collection as $name => $value) {
            switch ($type) {
                case 'config':
                    $result[$name] = $this->fallbackConfig($value);
                    break;
                case 'theme':
                    $result[$name] = $this->fallbackTheme($value);
                    break;
                case 'template':
                    $result[$name] = $this->fallbackTemplate($value);
            }
        }

        return $result;
    }

    /**
     * Apply fallbacks and fix configs
     *
     * @since 5.4.0
     */
    private function fallbackConfig($config)
    {
      $relatedEpisodes = isset($config['related-episodes']) ? $config['related-episodes'] : array();
      $subscribeButton = isset($config['subscribe-button']) ? $config['subscribe-button'] : array();
      $share = isset($config['share']) ? $config['share'] : array();

      return array(
            'activeTab' => (is_string($config['activeTab']) ? $config['activeTab'] : null),
            'subscribe-button' => array(
                'feed' => (is_string($subscribeButton['feed']) ? $subscribeButton['feed'] : null),
                'clients' => (is_array($subscribeButton['clients']) ? $subscribeButton['clients'] : [])
            ),
            'share' => array(
                'channels' => (is_array($share['channels']) ? array_unique($share['channels']) : []),
                'outlet' => $share['outlet'],
                'sharePlaytime' => (is_bool($share['sharePlaytime']) ? $share['sharePlaytime'] : true)
            ),
            'related-episodes' => array(
                'source' => (is_string($relatedEpisodes['source']) ? $relatedEpisodes['source'] : 'disabled'),
                'value' => $relatedEpisodes['value'] ?? null,
            ),
        );
    }

    /**
     * Apply fallbacks and fix theme
     *
     * @since 5.4.0
     */
    private function fallbackTheme($theme)
    {
        return array(
            'tokens' => array(
                'brand' => (is_string($theme['tokens']['brand']) ? $theme['tokens']['brand'] : '#E64415'),
                'brandDark' => (is_string($theme['tokens']['brandDark']) ? $theme['tokens']['brandDark'] : '#235973'),
                'brandDarkest' => (is_string($theme['tokens']['brandDarkest']) ? $theme['tokens']['brandDarkest'] : '#1A3A4A'),
                'brandLightest' => (is_string($theme['tokens']['brandLightest']) ? $theme['tokens']['brandLightest'] : '#E9F1F5'),
                'shadeDark' => (is_string($theme['tokens']['shadeDark']) ? $theme['tokens']['shadeDark'] : '#807E7C'),
                'shadeBase' => (is_string($theme['tokens']['shadeBase']) ? $theme['tokens']['shadeBase'] : '#807E7C'),
                'contrast' => (is_string($theme['tokens']['contrast']) ? $theme['tokens']['contrast'] : '#000'),
                'alt' => (is_string($theme['tokens']['alt']) ? $theme['tokens']['alt'] : '#fff'),
            ),
            'fonts' => array(
                'ci' => array(
                    'name' => (is_string($theme['fonts']['ci']['name']) ? $theme['fonts']['ci']['name'] : 'ci'),
                    'family' => (is_array($theme['fonts']['ci']['family']) ? $theme['fonts']['ci']['family'] : []),
                    'src' => (is_array($theme['fonts']['ci']['src']) ? $theme['fonts']['ci']['src'] : []),
                    'weight' => (isset($theme['fonts']['ci']['weight']) ? $theme['fonts']['ci']['weight'] : 800)
                ),
                'regular' => array(
                    'name' => (is_string($theme['fonts']['regular']['name']) ? $theme['fonts']['regular']['name'] : 'regular'),
                    'family' => (is_array($theme['fonts']['regular']['family']) ? $theme['fonts']['regular']['family'] : []),
                    'src' => (is_array($theme['fonts']['regular']['src']) ? $theme['fonts']['regular']['src'] : []),
                    'weight' => (isset($theme['fonts']['regular']['weight']) ? $theme['fonts']['regular']['weight'] : 300)
                ),
                'bold' => array(
                    'name' => (is_string($theme['fonts']['bold']['name']) ? $theme['fonts']['bold']['name'] : 'bold'),
                    'family' => (is_array($theme['fonts']['bold']['family']) ? $theme['fonts']['bold']['family'] : []),
                    'src' => (is_array($theme['fonts']['bold']['src']) ? $theme['fonts']['bold']['src'] : []),
                    'weight' => (isset($theme['fonts']['bold']['weight']) ? $theme['fonts']['bold']['weight'] : 700)
                )
            ),
        );
    }

    /**
     * Apply fallbacks and fix settings
     *
     * @since 5.4.0
     */
    private function fallbackSettings($settings)
    {
        global $content_width;
        return array(
            'source' => array(
                'selected' => (is_string($settings['source']['selected']) ? $settings['source']['selected']  : 'local'),
                'items' => array(
                  'local' => PODLOVE_WEB_PLAYER_PATH . '/web-player/',
                  'cdn' => 'https://cdn.podlove.org/web-player/5.x/',
              ),
            ),

            'enclosure' => (isset($settings['enclosure'])? $settings['enclosure'] : null),
            'legacy' => (is_bool($settings['legacy']) ? $settings['legacy'] : false),
            'defaults' => array(
                'theme' => (is_string($settings['defaults']['theme']) ? $settings['defaults']['theme']  : 'default'),
                'config' => (is_string($settings['defaults']['config']) ? $settings['defaults']['config']  : 'default'),
                'template' => (is_string($settings['defaults']['template']) ? $settings['defaults']['template']  : 'default'),
            ),
            'contentWidth' => $content_width,
        );
    }

    /**
     * Apply fallbacks and fix template
     *
     * @since 5.4.0
     */
    private function fallbackTemplate($template)
    {
        return (is_string($template) ? $template : '<root></root>');
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

        if (!is_array($options['settings'])) {
          $options['settings'] = [];
        }

        return array_replace_recursive($options ?? [], array(
            'configs' => $this->defaults('config', $options['configs'] ?? [], $this->defaultConfig),
            'themes' => $this->defaults('theme', $options['themes'] ?? [], $this->defaultTheme),
            'templates' => $this->defaults('template', $options['templates'] ?? [], $this->defaultTemplate),
            'settings' => $this->fallbackSettings($options['settings']),
        ));
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
