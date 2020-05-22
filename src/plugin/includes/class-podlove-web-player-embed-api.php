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
     * Plugin options
     * @since    5.0.2
     * @param    array    $options       The plugin options.
     */
    private $options;

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
        $this->options = new Podlove_Web_Player_Options($plugin_name);
        $this->interoperability = new Podlove_Web_Player_Interoperability($this->plugin_name);
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
                )
            );
        }

        register_rest_route(
          $this->plugin_name . '/' . 'shortcode',
          'config/(?P<config>[a-z0-9-]+)/theme/(?P<theme>[a-z0-9-]+)/show/(?P<show>[a-z0-9-]+)',
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
                  'show' => array(
                      'required' => true,
                  ),
              ),
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
        $post = get_post($postId);
        $customFields = get_post_custom($postId);

        $chapters = json_decode($customFields['chapters'][0]);
        $transcripts = json_decode($customFields['transcripts'][0]);

        $enclosure = explode("\n", $customFields['enclosure'][0]);

        $url = $enclosure[0];
        $fileSize = $enclosure[1];
        $mimeType = $enclosure[2];

        $duration = unserialize($enclosure[3]);

        return rest_ensure_response(
            array(
                'title' => $post->post_title,
                'duration' => $duration['duration'],
                'link' => get_permalink($post),
                'poster' => get_the_post_thumbnail_url($post),
                'audio' => array(
                    array(
                        'mimeType' => $mimeType,
                        'url' => $url,
                        'size' => $fileSize,
                        'title' => strtoupper($mimeType),
                    ),
                ),
                'chapters' => $chapters ? $chapters : array(),
                'show' => array(
                    'title' => get_bloginfo('name'),
                    'subtitle' => get_bloginfo('description'),
                    'link' => get_bloginfo('url'),
                ),
                'transcripts' => $transcripts ? $transcripts : array(),
            )
        );
    }

    public function episode(WP_REST_Request $request)
    {
        $publisherId = $request->get_param('id');
        return rest_ensure_response(\podlove_pwp5_attributes(array('post_id' => $publisherId)));
    }

    public function show(WP_REST_Request $request)
    {
        $slug = $request->get_param('slug');

        if( $this->interoperability->isPublisherActive() === false) {
          return rest_ensure_response(array());
        }

        if ($slug === 'default') {
            $args = array(
              'post_type' => 'podcast',
              'orderby' => array( 'post_date' => 'DESC' ),
              'post_status' => 'publish',
              'posts_per_page' => 20
            );
        } else {
          $args = array(
            'post_type' => 'podcast',
            'tax_query' => array(
                array(
                    'taxonomy' => 'shows',
                    'field' => 'slug',
                    'terms' => $slug,
                ),
            ),
          );
        }

        $show = new WP_Query($args);
        $posts = $show->get_posts();

        $result = array();

        foreach ($posts as &$post) {
            $episode = \podlove_pwp5_attributes(array('post_id' => $post->ID));
            $result[] = array(
                'title' => $episode['title'],
                'config' => $this->routes()['publisher'] . '/' . $post->ID,
                'duration' => $episode['duration'],
            );
        }

        return rest_ensure_response($result);
    }

    public function config(WP_REST_Request $request)
    {
        $configId = $request->get_param('config');
        $themeId = $request->get_param('theme');
        $showId = $request->get_param('show');

        $options = $this->options->read();
        $config = $options['configs'][$configId];

        $config['version'] = 5;
        $theme = array(
            'theme' => $options['themes'][$themeId],
        );

        $sources = $options['settings']['source']['items'];
        $selected = $options['settings']['source']['selected'];

        $share = array(
            'base' => $sources[$selected],
        );

        if (!is_null($config['share']['outlet'])) {
            $config['share']['outlet'] = $sources[$selected] . 'share.html';
        }

        // Disable subscribe when no clients are available
        $availableClients = $config['subscribe-button']['clients'];
        $config['subscribe-button'] = count($availableClients) > 0 ? $config['subscribe-button'] : null;

        if (isset($showId)) {
            $config['playlist'] = $this->routes()['show'] . '/' . $showId;
        }

        return rest_ensure_response(array_merge($config, $theme, $share));
    }
}
