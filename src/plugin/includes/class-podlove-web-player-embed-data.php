<?php

/**
 * Data Intefrace for embed data
 *
 *
 * @since      5.4.0
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Embed_Data
{
  /**
   * The ID of this plugin.

   * @since    5.4.0
   * @access   private
   * @var      string    $plugin_name    The ID of this plugin.
   */
  private $plugin_name;

  /**
   * Plugin options
   * @since    5.4.0
   * @access   private
   * @param    array    $options       The plugin options.
   */
  private $options;

  /**
   * Routes
   * @since    5.4.0
   * @access   private
   * @param    array    $routes       Embed Api Routes.
   */
  private $routes;

  /**
   * Initialize the class and set its properties.
   *
   * @since    5.4.0
   * @param      string    $plugin_name       The name of this plugin.
   * @param      string    $version           The version of this plugin.
   */
  public function __construct($plugin_name, $routes)
  {
    $this->plugin_name = $plugin_name;
    $this->routes = $routes;
    $this->options = new Podlove_Web_Player_Options($plugin_name);
  }

  // Episode Data
  public function episode($publisherId)
  {
    return \podlove_pwp5_attributes(array('post_id' => $publisherId));
  }

  /**
   * Helper to safely read custom field values.
   *
   * @param array  $customFields
   * @param string $key
   * @param int    $index
   * @param mixed  $default
   * @return mixed
   */
  private function getCustomField($customFields, $key, $index = 0, $default = null)
  {
    return $customFields[$key][$index] ?? $default;
  }

  public function post($postId)
  {
    $post = get_post($postId);
    $customFields = get_post_custom($postId);

    $chaptersRaw = $this->getCustomField($customFields, 'chapters');
    $transcriptsRaw = $this->getCustomField($customFields, 'transcripts');

    $chapters = $chaptersRaw !== null ? json_decode($chaptersRaw) : null;
    $transcripts = $transcriptsRaw !== null ? json_decode($transcriptsRaw) : null;

    $enclosureRaw = $this->getCustomField($customFields, 'enclosure', 0, '');
    $enclosure = explode("\n", $enclosureRaw);

    $url = $enclosure[0] ?? null;
    $fileSize = $enclosure[1] ?? null;
    $mimeType = $enclosure[2] ?? null;

    $durationRaw = $enclosure[3] ?? null;
    $duration = $durationRaw !== null ? json_decode($durationRaw, true) : null;

    return array(
      'title' => $post->post_title,
      'duration' => $duration['duration'] ?? null,
      'link' => get_permalink($post),
      'poster' => get_the_post_thumbnail_url($post),
      'audio' => array(
        array(
          'mimeType' => $mimeType,
          'url' => $url,
          'size' => $fileSize,
          'title' => $mimeType !== null ? strtoupper($mimeType) : null,
        ),
      ),
      'chapters' => $chapters ? $chapters : array(),
      'show' => array(
        'title' => get_bloginfo('name'),
        'subtitle' => get_bloginfo('description'),
        'link' => get_bloginfo('url'),
      ),
      'transcripts' => $transcripts ? $transcripts : array(),
    );
  }

  // Configuration
  public function config($configId, $themeId)
  {
    $options = $this->options->read();

    $configs = $options['configs'] ?? array();

    if (!isset($configs[$configId])) {
      $config = $configs['default'] ?? array();
    } else {
      $config = $configs[$configId];
    }

    $config['version'] = 5;

    $themes = $options['themes'] ?? array();

    $theme = array(
      'theme' => $themes[$themeId] ?? null,
    );

    $sourceSettings = $options['settings']['source'] ?? array();
    $sources = $sourceSettings['items'] ?? array();
    $selected = $sourceSettings['selected'] ?? null;

    $share = array(
      'base' => $sources[$selected] ?? null,
    );

    if (isset($config['share']['outlet']) && $config['share']['outlet'] !== null) {
      $config['share']['outlet'] = ($sources[$selected] ?? '') . 'share.html';
    }

    // Disable subscribe when no clients are available
    $availableClients = $config['subscribe-button']['clients'] ?? null;

    if (!is_array($availableClients)) {
      $availableClients = [];
    }

    $config['subscribe-button'] = count($availableClients) > 0 ? ($config['subscribe-button'] ?? null) : null;

    $relatedEpisodes = $config['related-episodes'] ?? array();

    switch ($relatedEpisodes['source'] ?? null) {
      case 'podcast':
        $config['playlist'] = $this->routes['podcast'] ?? null;
        break;
      case 'show':
        $config['playlist'] = ($this->routes['show'] ?? '') . '/' . ($relatedEpisodes['value'] ?? '');
        break;
    }

    return array_merge($config, $theme, $share);
  }

  // Related Episodes
  public function show($slug)
  {
    $show = new WP_Query(array(
      'post_type' => 'podcast',
      'tax_query' => array(
        array(
          'taxonomy' => 'shows',
          'field' => 'slug',
          'terms' => $slug,
        ),
      ),
    ));

    $posts = $show->get_posts();

    $result = array();

    foreach ($posts as &$post) {
      $episode = \podlove_pwp5_attributes(array('post_id' => $post->ID));
      $result[] = array(
        'title' => $episode['title'] ?? null,
        'episode' => $post->ID,
        'config' => $this->routes['publisher'] . '/' . $post->ID,
        'duration' => $episode['duration'] ?? null,
      );
    }

    return $result;
  }

  public function podcast()
  {
    $podcast = new WP_Query(array(
        'post_type' => 'podcast',
        'orderby' => array('post_date' => 'DESC'),
        'post_status' => 'publish',
        'posts_per_page' => 25,
      )
    );

    $posts = $podcast->get_posts();

    $result = array();

    foreach ($posts as &$post) {
      $episode = \podlove_pwp5_attributes(array('post_id' => $post->ID));
      $result[] = array(
        'title' => $episode['title'] ?? null,
        'episode' => $post->ID,
        'config' => $this->routes['publisher'] . '/' . $post->ID,
        'duration' => $episode['duration'] ?? null,
      );
    }

    return $result;
  }
}
