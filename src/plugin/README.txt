=== Plugin Name ===
Contributors: alexander-heimbuch,ericteuber
Donate link: https://podlove.org/donations
Tags: podcasting, audio
Requires at least: 5.3
Tested up to: 5.3
Requires PHP: 7.2
Stable tag: 5.0
License: MIT

The fast, flexible and responsive podcast player powered by podlove meta data.

== Description ==

Podlove Web Player is a HTML5 based web player for audio and video media files that is optimized and extended for the specific needs of podcasters.
It has a native integration with [Podlove Publisher](https://wordpress.org/plugins/podlove-podcasting-plugin-for-wordpress/), can be used as a shortcode and also plays well with Gutenberg Blocks.

### Made for Podcasters
* Podcast Chapters
* Transcripts
* Sharing & Embedding
* Download integrated
* Audio Controls, including playback speed
* Supports multiple audio file formats
* Supports live streams

### Theming & Templating

The Player is fully customizable in terms of

* Theme colors
* Font family and weight
* Templates and appearance

### Subscribe Button Integration

* Customizable Podcast Clients
* Integration in the Player

### Usage

There are basically four ways to use the Podlove Web Player:

#### 1 Manual Wordpress Shortcode

Use a simple shortcode in your posts and pages, and the Podlove Web Player will appear, playing any media file you want to assign. Basic usage:

```
[podlove-web-player
  theme="default"
  config="default"
  title="My episode title"
  subtitle="Episode Subtitle"
  poster="/files/path/to/poster.png"
  chapters="/files/path/to/chapters.json"
  transcripts="/files/path/to/transcripts.json"
  src="http://mysite.com/mymedia.mp3"
  duration="03:33"
]
```

Use an existing post with a media enclosure that is provided from plugins like Blubrry:

```
[podlove-web-player
  theme="default"
  config="default"
  episode="1234"
]
```

Or in case you have our [Podlove Publisher](https://wordpress.org/plugins/podlove-podcasting-plugin-for-wordpress/) installed:

```
[podlove-web-player
  theme="default"
  config="default"
  publisher="1234"
]
```

#### 2 Using Wordpress Gutenberg Blocks

You can choose between different references, like Podlove Publisher, Blocks or you can add the meta data manually. Right now the latter option is limited to a limited set of attributes, this will be expanded in further releases.

#### 3 Automatic Integration with Podcasting Engines

If you are using Podlove Publisher you should be able to select it in the Publisher Player settings.
In case you are still using Blubrry you can simply enable the automatic insertions on enclosures in the settings and choose the position.

### Help & Support

If you encounter any issue with the plugin or want to request a specific feature please reach out to our [Podlove Community](https://community.podlove.org/).

== Installation ==

1. Download the Podlove Web Player Plugin to your desktop.
2. If downloaded as a zip archive, extract the Plugin folder to your desktop.
3. With your FTP program, upload the Plugin folder to the wp-content/plugins folder in your WordPress directory online.
4. Go to Plugins screen and find the newly uploaded Plugin in the list.
5. Click Activate Plugin to activate it.

== Screenshots ==

1. Configurator
2. Theming
3. Templating
4. Gutenberg Block

== Changelog ==

= 5.0.0 =

- Podlove Web Player 5
- Complete rewrite of the plugin
- Podlove Publisher Integration
- Extensive configurator for Themes and Templates
- Support for Gutenberg Blocks
