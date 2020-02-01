<?php

/**
 * Modify podlove web player shortcode
 *
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <podlove@heimbu.ch>
 */
class Podlove_Web_Player_Shortcode {

  /**
	 * The ID of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

  /**
	 * Web Player options
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $options    The current player configuration.
	 */
  private $options;

  /**
	 * Shortcode APIs
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $api    The shortcode apis.
	 */
  private $api;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
    $this->plugin_name = $plugin_name;
    $this->version = $version;

    $this->options = new Podlove_Web_Player_Options( $this->plugin_name );
    $this->registerApis();
  }

  /**
	 * Shortcode Renderer
	 *
	 * @since    4.0.0
   * @param    array    $atts          Shortcode attributes.
   * @param    array    $content       Shortcode content.
	 */
  public function render( $atts, $content ) {
    $id = uniqid('player-');

    $attributes = $this->parseAttributes( $atts );
    $episode = $this->episode( $content );

    return $this->template( $id, $episode, $config );
  }

  /**
	 * Episode data
	 *
	 * @since    4.0.0
   * @param    array    $atts           Shortcode attributes.
	 */
  private function episode( $content ) {
    // check if $content is defined and a valid object -> shortcode contains a configuration object
    if ( trim($content) ) {
      return json_decode( $content );
    }

    // check if a post context is available call return the api with the matching endpoint
    global $post;

    if ( $post ) {
      return $this->apis['episode'] . '?post=' . $post->ID;
    }

    // TODO @ericteuber: publisher episode metadata here?
  }

  /**
	 * Template
	 *
	 * @since    4.0.0
   * @param    array    $atts           Shortcode attributes.
   * @param    array    $options        Plugin Options.
	 */

  /**
	 * Configuration
	 *
	 * @since    4.0.0
   * @param    array    $atts           Shortcode attributes.
   * @param    array    $options        Plugin Options.
	 */


  /**
	 * Shortcode Attribute Parser
	 *
	 * @since    4.0.0
   * @param    array    $atts       Shortcode attributes.
	 */
  private function parseAttributes( $atts = [] ) {
    global $post;

    if ( $post ) {
      $customFields = get_post_custom( $post->ID );
    }

    $config = array();

    $atts = array_change_key_case($atts, CASE_LOWER);

    $config['audio'] = array();
    $config['show'] = array();

    // type attributes
    foreach ($this->typeAttributes as $type ) {
      if ( array_key_exists( $type, $atts ) ) {
        $info = $this->mimeType( $atts[$type], $atts['size'] );

        $config['audio'][] = array(
          'url' => $atts[$type],
          'mimeType' => $info['mimeType'],
          'title' => strtoupper( $info['mimeType'] ),
          'size' => $info['size']
        );
      }
    }

    // data => bas64 encoded json string
    if ( array_key_exists( 'data', $atts ) ) {
      $config['data'] = unserialize( base64_decode( $atts['data'] ) );
    }

    // config url that replaces all other configs
    if ( array_key_exists( 'config', $atts ) && filter_var($atts['config'], FILTER_VALIDATE_URL) ) {
      $config['config'] = $atts['config'];
    }

    // chapters
    if ( $customFields && array_key_exists( 'chapters', $atts ) ) {
      $config['chapters'] = json_decode( $customFields[$atts['chapters']][0] );
    }

    // episode attributes
    foreach( $this->episodeAttributes as $attribute ) {
      if ( array_key_exists( $attribute, $atts ) ) {
        $config[$attribute] = $atts[$attribute];
      }
    }

    // show attributes
    foreach( $this->showAttributes as $attribute ) {
      if ( array_key_exists( $attribute, $atts ) ) {
        $config['show'][str_replace( 'show', '', $attribute)] = $atts[$attribute];
      }
    }

    return $config;
  }

  /**
	 * Template string generator
	 *
	 * @since    4.0.0
   * @param    string         $id             Unique player id.
   * @param    string/array   $episode        Url/object with configuration.
   * @param    string         $config         Url to player configuration.
   * @param    string         $template       Url to template
	 */
  private function template( $id, $episode, $config, $template ) {
    $embed = '
      <div class="podlove-web-player" id="$id" data-template="$template"></div>
      <script>
        podlovePlayer("$id", "$episode", "$config");
      </script>
    ';

    return strtr($embed, array(
      '$id' => $id,
      '$episode' => $episode,
      '$config' => $config,
      '$template' => $template
    ));
  }

  private function registerApis () {
    $this->apis = array(
      'episode' => esc_url_raw( rest_url( $this->plugin_name . '/' . 'shortcode' . '/' . $this->version . '/' . 'episode' ) ),
      'config' => esc_url_raw( rest_url( $this->plugin_name . '/' . 'shortcode' . '/' . $this->version . '/' . 'config' ) ),
      'template' => esc_url_raw( rest_url( $this->plugin_name . '/' . 'shortcode' . '/' . $this->version . '/' . 'template' ) )
    );

    register_rest_route( $this->apis['episode'],
      array(
        'methods' => 'GET',
        'callback' => array( $this, 'episodeApi' ),
        'args' => array (
          'post' => array(
            'required' => true
          )
        )
      )
    );
  }

  public function episodeApi ( WP_REST_Request $request ) {
    $postId = $request->get_param( 'post' );
    $post = get_post( $postId );

    $episode = array(
      'audio' => $this->postAudioFiles( $post ),
      'chapters' => $this->postChapters( $post ),
      'show' => $this->postShow(),

      'title' => $post['post_title'],
      'summary' => $post['post_excerpt'],
      'publicationDate' => $post['post_date'],
      'poster' => get_the_post_thumbnail( $postId, 'thumbnail' ),
      'link' => get_permalink( $postId )
    );

    return rest_ensure_response( $episode );
  }

  private function postAudioFiles( $post ) {
    if ( !$post || $post->post_type != 'post' || $post->post_status != 'publish' ) {
      return array();
    }

    $audio = array();

    $attachments = get_posts(
      array(
      'post_type' => 'attachment',
      'posts_per_page' => -1,
      'post_parent' => $postId,
      'post_mime_type' => 'audio',
      'exclude'=> get_post_thumbnail_id( $post )
      )
    );

    if ( !$attachments ) {
      return array();
    }

    // type attributes
    foreach ( $attachments as $attachment ) {
      $url = wp_get_attachment_url( $attachment );
      $mimeType = $attachment->post_mime_type;

      if ( !$url || !$mimeType ) {
        continue;
      }

      $audio[] = array(
        'url' => $url,
        'mimeType' => $mimeType,
        'title' => strtoupper( $mimeType ),
        'size' => filesize( $url )
      );
    }

    return $audio;
  }

  private function postChapters( $post ) {
    $customFields = get_post_custom( $post->ID );

    if ( !$customFields || !$customFields['chapters'] ) {
      return array();
    }

    return json_decode( $customFields['chapters'][0] );
  }

  private function postShow( ) {
    $blog = bloginfo();

    return array(
      'title' => $blog['name'],
      'subtitle' => $blog['description'],
      'link' => $blog['wpurl']
    );
  }
}
