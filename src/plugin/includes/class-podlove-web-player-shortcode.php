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
	 * Web Player options
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $options    The current player configuration.
	 */
  private $options;

  private $episodeAttributes = array( 'title', 'subtitle', 'poster', 'duration', 'link', 'summary', 'transcripts' );
  private $showAttributes = array( 'showtitle', 'showlink', 'showlink', 'showsubtitle', 'showposter' );
  private $typeAttributes = array( 'src', 'mp3', 'mp4', 'ogg', 'opus' );

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    4.0.0
	 * @param    string    $plugin_name       The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
    $this->options = new Podlove_Web_Player_Options( $plugin_name );
  }

  /**
	 * Shortcode Renderer
	 *
	 * @since    4.0.0
   * @param    array    $atts       Shortcode attributes.
	 */
  public function render( $atts, $content ) {
    $playerId = uniqid('player-');

    $defaults = $this->options->read();
    $attributes = $this->parseAttributes( $atts );

    // In case a config url is provided, ignore others
    if ( array_key_exists( 'config', $attributes ) ) {
      $config = $attributes['config'];
    } elseif ( array_key_exists( 'data', $attributes ) ) {
      $config = array_merge_recursive( $defaults, $attributes['data'] );
    } else {
      $config = array_merge_recursive( $defaults, $attributes );
    }

    return $this->template( $playerId, $config );
  }

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

    $simpleAttributes = array(  );
    $atts = array_change_key_case($atts, CASE_LOWER);

    $config['audio'] = array();
    $config['show'] = array();

    // type attributes
    foreach ($this->typeAttributes as $type ) {
      if ( array_key_exists( $type, $atts ) ) {
        $info = $this->mimeType($atts[$type]);

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
	 * Url mimeType detector
	 *
	 * @since    4.0.0
   * @param    string    $url       Url to file.
	 */
  private function mimeType( $url ) {
    $ch = curl_init( $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
    curl_setopt( $ch, CURLOPT_HEADER, 1 );
    curl_setopt( $ch, CURLOPT_NOBODY, 1 );
    curl_exec( $ch );
    return array( 'mimeType' => curl_getinfo( $ch, CURLINFO_CONTENT_TYPE ), 'size' => curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD ) );
  }

  /**
	 * Template string generator
	 *
	 * @since    4.0.0
   * @param    string    $playerId       Unique player id.
   * @param    array     $config         Player configuration.
	 */
  private function template( $playerId, $config ) {
    $config = json_encode( $config );

    $template = array(
      '<div class="podlove-web-player" id="', $playerId, '"></div>',
      '<script>', 'podlovePlayer("#', $playerId, '" ,', $config, ')', '</script>'
    );

    return join('', $template);
  }
}
