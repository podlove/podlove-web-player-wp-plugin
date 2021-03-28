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

    public function post($postId)
    {
        $post = get_post($postId);
        $customFields = get_post_custom($postId);

        $chapters = json_decode($customFields['chapters'][0]);
        $transcripts = json_decode($customFields['transcripts'][0]);

        $enclosure = explode("\n", $customFields['enclosure'][0]);

        $url = $enclosure[0];
        $fileSize = $enclosure[1];
        $mimeType = $enclosure[2];

        $duration = unserialize($enclosure[3]);

        return array(
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
        );
    }

    // Configuration
    public function config($configId, $themeId)
    {
        $options = $this->options->read();
        $config = $options['configs'][$configId];

        $config['version'] = 5;

        $theme = array(
            'theme' => $options['themes'][$themeId],
        );

        $sources = $options['settings']['source']['items'];
        $selected = $options['settings']['source']['selected'];
        $relatedEpisodes = $config['related-episodes'];

        $share = array(
            'base' => $sources[$selected],
        );

        if (!is_null($config['share']['outlet'])) {
            $config['share']['outlet'] = $sources[$selected] . 'share.html';
        }

        // Disable subscribe when no clients are available
        $availableClients = $config['subscribe-button']['clients'];

        if (!is_array($availableClients)) {
          $availableClients = [];
        }

        $config['subscribe-button'] = count($availableClients) > 0 ? $config['subscribe-button'] : null;

        switch ($relatedEpisodes['source']) {
            case 'podcast':
                $config['playlist'] = $this->routes['podcast'];
                break;
            case 'show':
                $config['playlist'] = $this->routes['show'] . '/' . $relatedEpisodes['value'];
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
                'title' => $episode['title'],
                'config' => $this->routes['publisher'] . '/' . $post->ID,
                'duration' => $episode['duration'],
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
                'title' => $episode['title'],
                'config' => $this->routes['publisher'] . '/' . $post->ID,
                'duration' => $episode['duration'],
            );
        }

        return $result;
    }
}
