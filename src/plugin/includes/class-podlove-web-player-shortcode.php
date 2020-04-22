<?php

/**
 * Modify podlove web player shortcode
 *
 *
 * @since      5.0.2
 * @package    Podlove_Web_Player
 * @subpackage Podlove_Web_Player/includes
 * @author     Alexander Heimbuch <github@heimbu.ch>
 */
class Podlove_Web_Player_Shortcode
{

    /**
     * The ID of this plugin.
     *
     * @since    5.0.2
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    5.0.2
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Web Player options
     *
     * @since    5.0.2
     * @access   private
     * @var      array    $options    The current player configuration.
     */
    private $options;

    /**
     * Shortcode API Routes
     *
     * @since    5.0.2
     * @access   private
     * @var      array    $routes    The shortcode routes.
     */
    private $routes;

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
     * @param    string    $plugin_name       The name of the plugin.
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->options = new Podlove_Web_Player_Options($this->plugin_name);
        $this->interoperability = new Podlove_Web_Player_Interoperability($this->plugin_name);
        add_action('init', [$this, 'set_routes']);
    }

    public function set_routes()
    {
        $api = new Podlove_Web_Player_Embed_API($this->plugin_name);
        $this->routes = $api->routes();
    }

    /**
     * Shortcode Renderer
     *
     * @since    5.0.2
     * @param    array    $atts          Shortcode attributes.
     * @param    array    $content       Shortcode content.
     */
    public function render($atts)
    {
        $id = uniqid('player-');

        if (!is_array($atts)) {
            $atts = [];
        }

        $attributes = array_change_key_case($atts, CASE_LOWER);
        $episode = $this->episode($attributes);
        $template = $this->template($attributes);
        $config = $this->config($attributes);

        return $this->html($id, $episode, $config, $template);
    }

    /**
     * Episode data
     *
     * @since    5.0.2
     * @param    array    $attributes      Shortcode attributes.
     */
    private function episode($attributes)
    {
        // if a post is provided
        if (isset($attributes['post'])) {
            return $this->routes['post'] . '/' . $attributes['post'];
        }

        // if attributes are provided
        if (isset($attributes['src']) || isset($attributes['mp3'])) {
            return $this->fromAttributes($attributes);
        }

        // if episode data is directly provided
        if (isset($attributes['episode'])) {
            return $attributes['episode'];
        }

        if ($this->interoperability->isPublisherActive()) {
          if (isset($attributes['publisher'])) {
              return $this->routes['publisher'] . '/' . $attributes['publisher'];
          }

          if (isset($attributes['post_id'])) {
              return $this->routes['publisher'] . '/' . $attributes['post_id'];
          }

          $id = get_the_ID();

          if (isset($id)) {
              return $this->routes['publisher'] . '/' . $id;
          }
        }
    }

    /**
     * Generates attributes for the attribute mode
     *
     * @since    5.0.2
     * @param    array    $attributes      Shortcode attributes.
     */
    private function fromAttributes($attributes)
    {
        global $post;
        $customFields = get_post_custom($post->ID);

        $chapters = $this->chapters(trim($attributes['chapters']), trim($customFields[$attributes['chapters']][0]));
        $transcripts = $this->transcripts(trim($attributes['transcripts']), trim($customFields[$attributes['transcripts']][0]));

        return array(
            'title' => $attributes['title'] ?? null,
            'subtitle' => $attributes['subtitle'] ?? null,
            'summary' => $attributes['summary'] ?? null,
            'duration' => $attributes['duration'] ?? null,
            'poster' => $attributes['poster'] ?? null,
            'chapters' => $chapters ?? array(),
            'transcripts' => $transcripts ?? array(),
            'audio' => $this->audio($attributes),
        );
    }

    /**
     * Creates the audio list
     *
     * @since    5.0.2
     * @param    array    $attributes      Shortcode attributes.
     */
    private function audio($attributes)
    {
        $size = 0;

        if (isset($attributes['size'])) {
          $size = $attributes['size'];
        }

        if (isset($attributes['filesize'])) {
          $size = $attributes['filesize'];
        }

        if (isset($attributes['mp3'])) {
            return array(
                array(
                    'url' => $attributes['mp3'],
                    'mimeType' => 'audio/mpeg',
                    'title' => 'MP3',
                    'size' => $size,
                ),
            );
        }

        if (isset($attributes['src'])) {
            return array(
                array(
                    'url' => $attributes['src'],
                    'mimeType' => $this->mimeType($attributes['src']),
                    'title' => strtoupper($this->mimeType($attributes['src'])),
                    'size' => $size,
                ),
            );
        }

        return array();
    }

    /**
     * Cheap mimeType detector
     *
     * @since    5.0.2
     * @param    string    $src      audio src url.
     */
    private function mimeType($src)
    {
        if ($this->endsWith($src, 'mp3')) {
            return 'audio/mpeg';
        }

        if ($this->endsWith($src, 'm4a')) {
            return 'audio/mp4';
        }

        if ($this->endsWith($src, 'oga')) {
            return 'audio/ogg';
        }

        if ($this->endsWith($src, 'opus')) {
            return 'audio/opus';
        }

        if ($this->endsWith($src, 'wav')) {
            return 'audio/wav';
        }

        return null;
    }

    /**
     * Creates the chapters list
     *
     * @since    5.0.2
     * @param    string    $attribute     provided attribute as string.
     * @param    string    $chapters      provided chapters as string.
     */
    private function chapters($attribute, $chapters)
    {
        if (!isset($attribute)) {
            return array();
        }

        if ($chapters === '') {
            return $attribute;
        }

        $result = json_decode($chapters);

        if ($result === '') {
            return array();
        }

        if ($result !== null) {
            return $result;
        }

        $chapterArray = array();
        preg_match_all('/((\d+:)?(\d\d?):(\d\d?)(?:\.(\d+))?) ([^<>\r\n]{3,}) ?(<([^<>\r\n]*)>\s*(<([^<>\r\n]*)>\s*)?)?\r?/', $chapters, $chapterArrayTemp, PREG_SET_ORDER);
        $chaptercount = count($chapterArrayTemp);
        for ($i = 0; $i < $chaptercount; ++$i) {
            $chapterArray[$i]['start'] = $chapterArrayTemp[$i][1];
            $chapterArray[$i]['title'] = htmlspecialchars($chapterArrayTemp[$i][6], ENT_QUOTES);
            if (isset($chapterArrayTemp[$i][9])) {
                $chapterArray[$i]['image'] = trim($chapterArrayTemp[$i][10], '<> ()\'');
            }
            if (isset($chapterArrayTemp[$i][7])) {
                $chapterArray[$i]['href'] = trim($chapterArrayTemp[$i][8], '<> ()\'');
            }
        }

        return $chapterArray;
    }

    /**
     * Creates the transcripts list
     *
     * @since   5.0.2
     * @param    string    $attribute     provided attribute as string.
     * @param    string    $transcripts   provided transcripts as string.
     */
    private function transcripts($attribute, $transcripts)
    {
      if (!isset($attribute)) {
        return array();
      }

      if ($transcripts === '') {
        return $attribute;
      }

      $result = json_decode($transcripts);

      if ($result === '') {
          return array();
      }

      if ($result !== null) {
          return $result;
      }
    }

    /**
     * Template
     *
     * @since    5.0.2
     * @param    array    $attributes      Shortcode attributes.
     */
    private function template($attributes)
    {
        $template = $attributes['template'] ?? 'default';
        $options = $this->options->read();

        return $options['templates'][$template];
    }

    /**
     * Configuration
     *
     * @since    5.0.2
     * @param    array    $attributes           Shortcode attributes.
     */
    private function config($attributes)
    {
        $config = $attributes['config'] ?? 'default';
        $theme = $attributes['theme'] ?? 'default';

        return $this->routes['config'] . '/' . $config . '/' . 'theme' . '/' . $theme;
    }

    /**
     * Template string generator
     *
     * @since    5.0.2
     * @param    string         $id             Unique player id.
     * @param    string/array   $episode        Url/object with configuration.
     * @param    string         $config         Url to player configuration.
     * @param    string         $template       Url to template
     */
    private function html($id, $episode, $config, $template)
    {
        $embed = '
      <div class="podlove-web-player" id="$id">$template</div>
      <script>
        podlovePlayer("#$id", $episode, "$config");
      </script>
    ';

        return strtr($embed, array(
            '$id' => $id,
            '$episode' => is_string($episode) ? '"' . $episode . '"' : json_encode($episode),
            '$config' => $config,
            '$template' => $template,
        ));
    }

    /**
     * Helpers
     *
     * @since    5.0.2
     * @param    string    $haystack           Input string
     * @param    string    $needle             String to search for
     */
    private function endsWith($haystack, $needle)
    {
        return strrpos($haystack, $needle) + strlen($needle) === strlen($haystack);
    }
}
