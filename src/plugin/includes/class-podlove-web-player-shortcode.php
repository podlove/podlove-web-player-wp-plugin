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
	 * Shortcode API Routes
	 *
	 * @since    4.0.0
	 * @access   private
	 * @var      array    $api    The shortcode apis.
	 */
  private $routes;

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
    $api = new Podlove_Web_Player_Embed_API( $this->plugin_name );
    $this->routes = $api->routes();
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

    $attributes = array_change_key_case($atts, CASE_LOWER);
    $episode = $this->episode( $attributes );
    $template = $this->template( $attributes );
    $config = $this->config( $attributes );

    return $this->html( $id, $episode, $config, $template );
  }

  /**
	 * Episode data
	 *
	 * @since    4.0.0
   * @param    array    $attributes      Shortcode attributes.
	 */
  private function episode( $attributes ) {
    if ( $attributes['post'] ) {
      return $this->routes['post'] . '/' . $attributes['post'];
    }

    // TODO @ericteuber: publisher episode metadata here?
    return null;
  }

  /**
	 * Template
	 *
	 * @since    4.0.0
   * @param    array    $attributes      Shortcode attributes.
	 */
  private function template( $attributes ) {
    $template = $attributes['template'] ? $attributes['template'] : 'default';
    $options = $this->options->read();

    return $options['templates'][$template];
  }

  /**
	 * Configuration
	 *
	 * @since    4.0.0
   * @param    array    $attributes           Shortcode attributes.
	 */
  private function config( $attributes ) {
    $config = $attributes['config'] ? $attributes['config'] : 'default';
    $theme = $attributes['theme'] ? $attributes['theme'] : 'default';

    return $this->routes['config'] . '/' . $config . '/' . 'theme' . '/' . $theme;
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
  private function html( $id, $episode, $config, $template ) {
    $embed = '
      <div class="podlove-web-player" id="$id">$template</div>
      <script>
        podlovePlayer("#$id", "$episode", "$config");
      </script>
    ';

    return strtr($embed, array(
      '$id' => $id,
      '$episode' => $episode,
      '$config' => $config,
      '$template' => $template
    ));
  }
}
