<?php

/**
 * Modify podlove web player Enclosure
 *
 *
 * @since      1.0.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <podlove@heimbu.ch>
 */
class Podlove_Web_Player_Enclosure {

  /**
	 * Web Player options
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $options    The current player configuration.
	 */
  private $options;

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
	 * Enclosure Renderer
	 *
	 * @since    4.0.0
   * @param    array    $content       post content.
	 */
  public function render( $content ) {
    global $post;

    $options = $this->options->read();
    $enclosures = $this->getEnclosures( $post );
    $shortcodes = [];

    if ( !$enclosures ) {
      return $content;
    }

    foreach( $enclosures as $enclosure ) {
      $shortcodes[] = do_shortcode( '[podlove-web-player data="' . base64_encode( serialize( $enclosure ) ) . '"]' );
    }

    if ( $options['bottom'] ) {
      return $content . implode('', $shortcodes);
    }

    return $content = implode('', $shortcodes) . $content;
  }

  /**
	 * Enclosure Meta Attributes
	 *
	 * @since    4.0.0
   * @param    array    $post       post object.
	 */
  private function getEnclosures( $post ) {
    $customFields = get_post_custom( $post->ID );

    $enclosures = $customFields['enclosure'];
    $chapters = $customFields['chapters'][0];
    $transcripts = $customFields['transcripts'];

    $pung = array();

    if ( !is_array( $enclosures ) ) {
      return $pung;
    }

    foreach( $enclosures as $enclosure ) {
      $pung[] = $this->getPlayerMeta( array( 'enclosure' => $enclosure, 'chapters' => $chapters, 'transcripts' => $transcripts ), $post );
    }

    return apply_filters( 'get_enclosed', $pung, $post_id );
  }

  /**
	 * Enclosure Meta Attributes
	 *
	 * @since    4.0.0
   * @param    array    $post       post object.
	 */
  private function getPlayerMeta( $meta, $post ) {
    $enclosure     = explode( "\n", $meta['enclosure'] );

    $url           = $enclosure[0];
    $fileSize      = $enclosure[1];
    $mimeType      = $enclosure[2];

    $duration      = unserialize( $enclosure[3] );
    $chapters      = json_decode( $meta['chapters'] );

    return array(
      'title'     => $post->post_title,
      'duration'  => $duration['duration'],
      'link'      => get_permalink( $post ),
      'poster'    => get_the_post_thumbnail_url( $post ),
      'audio'     => array(
        'mimeType'    => $mimeType,
        'url'         => $url,
        'size'        => $fileSize,
        'title'       => strtoupper( $mimeType )
      ),
      'chapters'  => $chapters ? $chapters : array(),
      'show'      => array(
        'title'       => get_bloginfo( 'name' ),
        'subtitle'    => get_bloginfo( 'description' ),
        'link'        => get_bloginfo( 'url' )
      )
    );
  }
}
