<?php

/**
 * REST interface for the podlove web player embed
 *
 *
 * @since      5.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <podlove@heimbu.ch>
 */
class Podlove_Web_Player_Embed_API
{
  /**
   * The ID of this plugin.

   * @since    5.0.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Plugin options
   * @since    5.0.0
   * @param    array    $options       The plugin options.
   */
  private $options;

  /**
   * Initialize the class and set its properties.
   *
   * @since    5.0.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version           The version of this plugin.
   */
  public function __construct($plugin_name)
  {
    $this->plugin_name = $plugin_name;
    $this->options = new Podlove_Web_Player_Options($plugin_name);
  }

  /**
   * Define API paths
   *
   * @since    5.0.0
   */
  public function routes()
  {
    return array(
      'post' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'post')),
      'media' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'media')),
      'config' => esc_url_raw(rest_url($this->plugin_name . '/' . 'shortcode' . '/' . 'config'))
    );
  }

  /**
   * Register the API routes
   *
   * @since    5.0.0
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
            'required' => true
          )
        )
      )
    );


    register_rest_route(
      $this->plugin_name . '/' . 'shortcode',
      'config/(?P<config>\w+)/theme/(?P<theme>\w+)',
      array(
        'methods' => 'GET',
        'callback' => array($this, 'config'),
        'args' => array(
          'config' => array(
            'required' => true
          ),
          'theme' => array(
            'required' => true
          )
        )
      )
    );
  }

  /**
   * Validates the API payload
   *
   * @since    5.0.0
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

    $enclosure     = explode("\n", $customFields['enclosure'][0]);

    $url           = $enclosure[0];
    $fileSize      = $enclosure[1];
    $mimeType      = $enclosure[2];

    $duration      = unserialize($enclosure[3]);

    return rest_ensure_response(
      array(
        'title'     => $post->post_title,
        'duration'  => $duration['duration'],
        'link'      => get_permalink($post),
        'poster'    => get_the_post_thumbnail_url($post),
        'audio'     => array(
          array(
            'mimeType'    => $mimeType,
            'url'         => $url,
            'size'        => $fileSize,
            'title'       => strtoupper($mimeType)
          )
        ),
        'chapters'  => $chapters ? $chapters : array(),
        'show'      => array(
          'title'       => get_bloginfo('name'),
          'subtitle'    => get_bloginfo('description'),
          'link'        => get_bloginfo('url')
        ),
        'transcripts' => $transcripts ? $transcripts : array()
      )
    );
  }

  public function config(WP_REST_Request $request)
  {
    $configId = $request->get_param('config');
    $themeId = $request->get_param('theme');

    $options = $this->options->read();

    $config = $options['configs'][$configId];
    $theme = array(
      'theme' => $options['themes'][$themeId]
    );

    $sources = $options['settings']['source']['items'];
    $selected = $options['settings']['source']['selected'];

    $share = array(
      'base' => $sources[$selected],
    );

    $config['share']['outlet'] = $sources[$selected] . 'share.html';

    return rest_ensure_response(array_merge($config, $theme, $share));
  }
}
