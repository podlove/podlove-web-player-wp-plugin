<?php

/**
 * REST interface for the podlove web player embed
 *
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Embed_API
{
    /**
     * The ID of this plugin.

     * @since    5.0.2
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * Plugin data
     * @since    5.0.2
     * @param    array    $data       The plugin data.
     */
    private $data;

    /**
     * Interoperability Object
     *
     * @since    5.0.6
     * @access   private
     * @var      object   $interoperability   The player interoperability.
     */
    private $interoperability;

    /**
     * Initialize the class and set its properties.
     *
     * @since    5.0.2
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version           The version of this plugin.
     */
    public function __construct($plugin_name)
    {
        $this->plugin_name = $plugin_name;
        $this->interoperability = new Podlove_Web_Player_Interoperability($this->plugin_name);
        add_action('init', [$this, 'defineData']);
    }

    public function defineData()
    {
        $this->data = new Podlove_Web_Player_Embed_Data($this->plugin_name, $this->routes());
    }

    /**
     * Define API paths
     *
     * @since    5.0.2
     */
    public function routes()
    {
        return array(
            'post' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'post')),
            'publisher' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'publisher')),
            'config' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'config')),
            'show' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'show')),
            'podcast' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'podcast')),
        );
    }

    /**
     * Register the API routes
     *
     * @since    5.0.2
     */
    public function registerRoutes()
    {
        register_rest_route(
            $this->plugin_name . '/' . 'shortcode',
            'post/(?P<id>\d+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'post'),
                'args' => array(
                    'id' => array(
                        'required' => true,
                    ),
                ),
                'permission_callback' => '__return_true',
            )
        );

        if ($this->interoperability->isPublisherActive()) {
            register_rest_route(
                $this->plugin_name . '/' . 'shortcode',
                'publisher/(?P<id>\d+)',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'episode'),
                    'args' => array(
                        'id' => array(
                            'required' => true,
                        ),
                    ),
                    'permission_callback' => '__return_true',
                )
            );

            register_rest_route(
                $this->plugin_name . '/' . 'shortcode',
                'show/(?P<slug>[a-z0-9-]+)',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'show'),
                    'args' => array(
                        'slug' => array(
                            'required' => true,
                        ),
                    ),
                    'permission_callback' => '__return_true',
                )
            );

            register_rest_route(
                $this->plugin_name . '/' . 'shortcode',
                'podcast',
                array(
                    'methods' => 'GET',
                    'callback' => array($this, 'podcast'),
                    'permission_callback' => '__return_true',
                )
            );
        }

        register_rest_route(
            $this->plugin_name . '/' . 'shortcode',
            'config/(?P<config>[a-z0-9-]+)/theme/(?P<theme>[a-z0-9-]+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'config'),
                'args' => array(
                    'config' => array(
                        'required' => true,
                    ),
                    'theme' => array(
                        'required' => true,
                    ),
                ),
            )
        );

        // legacy support
        register_rest_route(
            $this->plugin_name . '/' . 'shortcode',
            'config/(?P<config>[a-z0-9-]+)/theme/(?P<theme>[a-z0-9-]+)/(?P<path>[\S]+)',
            array(
                'methods' => 'GET',
                'callback' => array($this, 'config'),
                'args' => array(
                    'config' => array(
                        'required' => true,
                    ),
                    'theme' => array(
                        'required' => true,
                    ),
                ),
                'permission_callback' => '__return_true',
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
            default:
                return false;
        }
    }

    public function post(WP_REST_Request $request)
    {
        $postId = $request->get_param('id');
        return rest_ensure_response($this->data->post($postId));
    }

    public function episode(WP_REST_Request $request)
    {
        $publisherId = $request->get_param('id');
        return rest_ensure_response($this->data->episode($publisherId));
    }

    public function show(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');

        if ($this->interoperability->isPublisherActive() === false) {
            return rest_ensure_response(array());
        }

        return rest_ensure_response($this->data->show($slug));
    }

    public function podcast()
    {
        if ($this->interoperability->isPublisherActive() === false) {
            return rest_ensure_response(array());
        }

        return rest_ensure_response($this->data->podcast());
    }

    public function config(WP_REST_Request $request)
    {
        $configId = $request->get_param('config');
        $themeId = $request->get_param('theme');

        return rest_ensure_response($this->data->config($configId, $themeId));
    }
}
