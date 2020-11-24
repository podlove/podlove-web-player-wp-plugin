<?php

/**
 * REST interface for the podlove web player configurator
 *
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Admin_API
{
    /**
     * The ID of this plugin.
     *
     * @since    5.0.2
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    5.0.2
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Plugin options
     */
    private $options;

    /**
     * Initialize the class and set its properties.
     *
     * @since    5.0.2
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->options = new Podlove_Web_Player_Options($plugin_name);
    }

    /**
     * Define API paths
     *
     * @since    5.0.2
     */
    public function routes()
    {
        return array(
            'bootstrap' => esc_url_raw(rest_url($this->plugin_name . '/' . $this->version . '/' . 'bootstrap')),
            'config' => esc_url_raw(rest_url($this->plugin_name . '/' . $this->version . '/' . 'config')),
            'theme' => esc_url_raw(rest_url($this->plugin_name . '/' . $this->version . '/' . 'theme')),
            'template' => esc_url_raw(rest_url($this->plugin_name . '/' . $this->version . '/' . 'template')),
            'settings' => esc_url_raw(rest_url($this->plugin_name . '/' . $this->version . '/' . 'settings')),
            'shows' => esc_url_raw(rest_url($this->plugin_name . '/' . $this->version . '/' . 'shows')),
        );
    }

    /**
     * Register the API routes
     *
     * @since    5.0.2
     */
    public function registerRoutes()
    {
        register_rest_route($this->plugin_name . '/' . $this->version, 'bootstrap',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'bootstrap'),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'config/(?P<id>[a-z0-9-]+)',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'saveConfig'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                    ),
                    'subscribe-button' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('config/subscribe-button', $param);
                        },
                    ),
                    'share' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('config/share', $param);
                        },
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'theme/(?P<id>[a-z0-9-]+)',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'saveTheme'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                    ),
                    'tokens' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('theme/tokens', $param);
                        },
                    ),
                    'fonts' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('theme/fonts', $param);
                        },
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'template/(?P<id>[a-z0-9-]+)',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'saveTemplate'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                    ),
                    'template' => array(
                        'required' => true,
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'config/(?P<id>[a-z0-9-]+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'deleteConfig'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('delete/id', $param);
                        },
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'theme/(?P<id>[a-z0-9-]+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'deleteTheme'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('delete/id', $param);
                        },
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'template/(?P<id>[a-z0-9-]+)',
            array(
                'methods' => 'DELETE',
                'callback' => array($this, 'deleteTemplate'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('delete/id', $param);
                        },
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'settings',
            array(
                'methods' => 'POST',
                'callback' => array($this, 'saveSettings'),
                'args' => array(
                    'source' => array(
                        'required' => true,
                        'validate_callback' => function ($param) {
                            return $this->validate('settings/source', $param);
                        },
                    ),
                ),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );

        register_rest_route($this->plugin_name . '/' . $this->version, 'shows',
            array(
                'methods' => 'GET',
                'callback' => array($this, '  '),
                'permission_callback' => array($this, 'api_permissions'),
            )
        );
    }

    /**
     * Validates the API payload
     *
     * @since    5.0.2
     */
    public function validate($type, $value)
    {
        switch ($type) {
            case 'config/subscribe-button':
                return
                array_key_exists('feed', $value) &&
                array_key_exists('clients', $value);

            case 'config/share':
                return
                array_key_exists('channels', $value) &&
                array_key_exists('outlet', $value) &&
                array_key_exists('sharePlaytime', $value);

            case 'theme/tokens':
                return
                array_key_exists('brand', $value) &&
                array_key_exists('brandDark', $value) &&
                array_key_exists('brandDarkest', $value) &&
                array_key_exists('brandLightest', $value) &&
                array_key_exists('shadeDark', $value) &&
                array_key_exists('shadeBase', $value) &&
                array_key_exists('contrast', $value) &&
                array_key_exists('alt', $value);

            case 'theme/fonts':
                return
                array_key_exists('ci', $value) &&
                array_key_exists('regular', $value) &&
                array_key_exists('bold', $value);

            case 'delete/id':
                return $value !== 'default';

            case 'settings/source':
                return $value === 'local' || $value === 'cdn';

            default:
                return false;
        }
    }

    /**
     * Check API permissions
     *
     * @since    5.0.2
     */
    public function api_permissions()
    {
      return current_user_can('manage_options');
    }

    /**
     * Save API config
     *
     * @since    5.0.2
     */
    public function saveConfig(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $configId = $request->get_param('id');

        $options['configs'] = array_merge($options['configs'], array(
            $configId => array(
                'activeTab' => $request->get_param('activeTab'),
                'subscribe-button' => $request->get_param('subscribe-button'),
                'share' => $request->get_param('share'),
                'related-episodes' => $request->get_param('related-episodes')
            ),
        ));

        $this->options->update($options);
        $options = $this->options->read();

        return rest_ensure_response($options['configs'][$configId]);
    }

    /**
     * Save API theme
     *
     * @since    5.0.2
     */
    public function saveTheme(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $themeId = $request->get_param('id');

        $options['themes'] = array_merge($options['themes'], array(
            $themeId => array(
                'tokens' => $request->get_param('tokens'),
                'fonts' => $request->get_param('fonts'),
            ),
        ));

        $this->options->update($options);
        $options = $this->options->read();

        return rest_ensure_response($options['themes'][$themeId]);
    }

    /**
     * Save API template
     *
     * @since    5.0.2
     */
    public function saveTemplate(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $templateId = $request->get_param('id');

        $options['templates'] = array_merge($options['templates'], array(
            $templateId => $request->get_param('template'),
        ));

        $this->options->update($options);
        $options = $this->options->read();

        return rest_ensure_response($options['templates'][$templateId]);
    }

    /**
     * Delete API config
     *
     * @since    5.0.2
     */
    public function deleteConfig(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $configId = $request->get_param('id');

        unset($options['configs'][$configId]);

        $this->options->update($options);
        return rest_ensure_response(true);
    }

    /**
     * Delete API theme
     *
     * @since    5.0.2
     */
    public function deleteTheme(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $themeId = $request->get_param('id');

        unset($options['themes'][$themeId]);

        $this->options->update($options);
        return rest_ensure_response(true);
    }

    /**
     * Delete API template
     *
     * @since    5.0.2
     */
    public function deleteTemplate(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $templateId = $request->get_param('id');

        unset($options['templates'][$templateId]);

        $this->options->update($options);
        return rest_ensure_response(true);
    }

    /**
     * Save settings
     *
     * @since    5.0.2
     */
    public function saveSettings(WP_REST_Request $request)
    {
        $options = $this->options->read();
        $source = $request->get_param('source');
        $enclosure = $request->get_param('enclosure');
        $legacy = $request->get_param('legacy');
        $defaults = $request->get_param('defaults');

        $options['settings']['source']['selected'] = $source;
        $options['settings']['enclosure'] = $enclosure;
        $options['settings']['legacy'] = $legacy;
        $options['settings']['defaults'] = $defaults;

        $this->options->update($options);
        $options = $this->options->read();

        return rest_ensure_response($options['settings']);
    }

    /**
     * Load initial data
     *
     * @since    5.0.2
     */
    public function bootstrap()
    {
        $data = $this->options->read();
        $data['presets'] = $this->options->presets();
        $data['shows'] = $this->shows()->data;

        return rest_ensure_response($data);
    }

    /**
     * List all available shows
     *
     * @since 5.1.2
     */
    public function shows()
    {
        $result = get_terms('shows', array(
          'hide_empty' => 0,
          'fields' => 'all',
        ));

        return rest_ensure_response($result);
    }
}
