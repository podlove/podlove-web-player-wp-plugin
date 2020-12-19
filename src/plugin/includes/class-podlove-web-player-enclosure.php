<?php

/**
 * Podlove web player Enclosure
 *
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Enclosure {

  /**
	 * Web Player options
	 *
	 * @since    5.0.2
	 * @access   private
	 * @var      object    $options    The current player configuration.
	 */
  private $options;

  /**
	 * Initialize the class and set its properties.
	 *
	 * @since    5.0.2
	 * @param    string    $plugin_name       The name of the plugin.
	 */
	public function __construct( $plugin_name ) {
    $this->options = new Podlove_Web_Player_Options( $plugin_name );
    $this->interoperability = new Podlove_Web_Player_Interoperability( $plugin_name );
  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    5.4.6
   */
  public function enqueue_scripts()
  {
      // If the publisher is active but the player wasn't selected disable script enqueue
      if ($this->interoperability->isPublisherActive() && !$this->interoperability->isPlayerActiveInPublisher()) {
        return;
      }

      $options = $this->options->read();
      $sources = $options['settings']['source']['items'];
      $selected = $options['settings']['source']['selected'];
      $legacy = $options['settings']['legacy'];

      if ($legacy) {
          wp_enqueue_script($this->plugin_name . '-polyfills', $sources[$selected] . 'polyfills.js', array(), $this->version, false);
      }

      wp_enqueue_script($this->plugin_name . '-player', $sources[$selected] . 'embed.js', array(), $this->version, false);
      wp_enqueue_script($this->plugin_name . '-player-cache', PODLOVE_WEB_PLAYER_PATH . '/js/cache.js', array(), $this->version, false);
  }

  /**
	 * Enclosure Renderer
	 *
	 * @since    5.0.2
   * @param    array    $content       post content.
	 */
  public function render($content)
  {
      global $post;

      $options      = $this->options->read();
      $customFields = get_post_custom($post->ID);

      if (has_shortcode($content, 'podloveaudio') || has_shortcode($content, 'podlove-web-player') || has_shortcode($content, 'podlove-episode-web-player')) {
        $this->enqueue_scripts();
      }

      // Publisher ❤️
      if (get_post_type() == 'podcast') {
          return $content;
      }

      // Legacy Blubörry? Blueberry??? 💩
      $enclosure = $customFields['enclosure'][0] ?? null;

      if (!$enclosure) {
          return $content;
      }

      $shortcode = do_shortcode('[podlove-web-player post="' . $post->ID . '"]');

      if ($options['settings']['enclosure'] == 'bottom') {
          return $content . $shortcode;
      }

      return $shortcode . $content;
    }
}
