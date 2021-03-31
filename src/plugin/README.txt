=== Plugin Name ===
Contributors: alexander-heimbuch,ericteuber
Donate link: https://podlove.org/donations
Tags: podcasting, audio
Requires at least: 5.3
Tested up to: 5.6
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
`
[podlove-web-player
  theme="default"
  config="default"
  title="My episode title"
  subtitle="Episode Subtitle"
  poster="/files/path/to/poster.png"
  chapters="/files/path/to/chapters.json"
  transcripts="/files/path/to/transcripts.json"
  playlist="/files/path/to/playlist.json"
  src="http://mysite.com/mymedia.mp3"
  size="1337"
  show="My show title"
  duration="03:33"
]
`

Use an existing post with a media enclosure that is provided from plugins like Blubrry:

`
[podlove-web-player
  theme="default"
  config="default"
  post="1234"
]
`

Or in case you have our [Podlove Publisher](https://wordpress.org/plugins/podlove-podcasting-plugin-for-wordpress/) installed:

`
[podlove-web-player
  theme="default"
  config="default"
  publisher="1234"
]
`

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

= 5.4.11 =

- Fix an issue that could lead to invisible player

= 5.4.10 =

- Update wordpress repository

= 5.4.9 =

- Fix PHP warnings for array_replace and array_merge
- Add logic to hide player while it's initializing
- Improve README

= 5.4.8 =

- Remove conditonal loading due to WordPress quirkyness :/

= 5.4.7 =

- Improve page performance by only adding scripts on pages with a player
- Harden lifecycle, only render player once all scripts are available (this may lead to slower page rendering)

= 5.4.5 =

- Fix PHP warnings
- Minimize cache.js size
- Only add api requests to cache

= 5.4.4 =

- Brings back show tag for shortcode
- Fixes shortcode registration

= 5.4.3 =

- Fixes an issue with persisting embed player and share playtime config
- Fixes an issue with rendering the player enclosure in feed

= 5.4.2 =

- Fixes an issue with persisting the embedding settings
- Improves compatability if the plugin is activated but not enabled in the Podlove Publisher settings

= 5.4.1 =

- Fixes issue with active default tab
- Fixes an issue with invalid stored configurations

= 5.4.0 =

- Fix issues with default configuration saving
- Fix menu highlighting
- Add assistive texts to theming
- Feature add ability to define related episodes
- Improve player loading and caching performance
- Update Podlove Web Player to v5.3.1
- Breaking: removes ability to define a show as a related episode in the publisher player template

= 5.3.1 =

- Fix API requests to make configurator working again

= 5.3.0 =

- Fix API warnings
- Update Podlove Web Player to v5.3.0

= 5.2.9 =

- Update Podlove Web Player to v5.2.0

= 5.2.8 =

- Rollback changes

= 5.2.7 =

- Fixes issue with channel selection on default

= 5.2.6 =

- Fixes issue in the configurator on systems running on subdomains

= 5.2.5 =

- Fixes issue with theme font editing

= 5.2.4 =

- Update Podlove Web Player package

= 5.2.3 =

- Update Podlove Web Player package to fix font names
- Add ability to create fonts in themes with names
- Fix multi site support directory name

= 5.2.2 =

- Update Podlove Web Player package to prevent unitential preload
- Add shortcode attribute for playlists

= 5.2.1 =

- Update Podlove Web Player package to address network related errors

= 5.2.0 =

- Extract defaults to fix incorrect persisted paths
- Add the ability to use Podlove Publisher shows to display related episodes

= 5.1.2 =

- Fix a bug that causes a warning on pages

= 5.1.1 =

- Fix a bug that prevents adding/updating configs or templates

= 5.1.0 =

- Set default configuration, theme and template for player instances
- Add show title attribute in short codes
- Add ability to select a blueprint while creating a config, theme or template
- Add full configuration with all subscribe-button options enabled
- Add Podlove Web Player 4 (classic) template as blueprint
- Add themes for Wordpress TwentyTwenty and Wordpress TwentyNineteen
- Fix bug that prevents complete uninstall
- Upgrade Podlove Web Player to 5.1.4

= 5.0.14 =

- Support Wordpress MultiSite instances, in network activated mode all settings are stored globally on the site
- Fix player embed base path on instances where wordpress is running in subdirectories

= 5.0.13 =

- Debounce player embedding until page is fully loaded
- Don't show the embed code when deactivated

= 5.0.12 =

- Sort menu items by defaukt
- Bump Podlvoe Player to 5.1.3
- Fix missing demo content

= 5.0.11 =

- Fix a bug that prevented removing configurations
- Add a compatability class to support Wordpress theme TwentyTwenty
- Relocated the delete button in the configurator

= 5.0.10 =

- Fix list ordering
- Improve theme color selection
- Fix theme configuration
- Bump player version

= 5.0.9 =

- Fix variable collision with Podlove Publisher

= 5.0.8 =

- Fix broken import path

= 5.0.7 =

- Prepare Podlove Publisher compatability

= 5.0.6 =

- Fix wordpress release process

= 5.0.5 =

- Change input type for the custom gutenberg duration block

= 5.0.4 =

- Fixes a bug with the mimeType detection for m4a audio files

= 5.0.3 =

- Fixes a bug that prevents resetting the "transform post enclosure" option
- Set "local" as the default source for the player

= 5.0.2 =

- Update to Podlove Web Player 5.1.1

= 5.0.1 =

- Fix wordpress readme

= 5.0.0 =

- Podlove Web Player 5
- Complete rewrite of the plugin
- Podlove Publisher Integration
- Extensive configurator for Themes and Templates
- Support for Gutenberg Blocks

= 2.1.0 =
- mejs update
- simplified, modernised look
- responsive layout for mobile devices

= 2.0.19 =
- mejs update
- link to timecode
- default posters configurable
- get chapters from other sources
- Style Editor
- smaller and bigger player styles
- save playtime in cookies
- mp4chaps image support

= 2.0.18 =
- compatible with WordPress theme Twenty-Fourteen
- read plugin version dynamically in settings.php

= 2.0.17 =
- fixes an error on apaches without mod_headers

= 2.0.16 =
- fixes unspecific css selector bug, introduced in last version
- fixes removing elements other than sources
- fixes false milliseconds

= 2.0.15 =
- small fixes
- .htaccess examples added in /help

= 2.0.14 =
- style improvements
- wordpress twenty thirteen theme compatibility
- FireFox AAC fix
- summary style fix
- XSS Firefox Bugfix
- jslint valid whitespace

= 2.0.13 =
- fix IE8 support
- more valid/better js code

= 2.0.12 =
- increase version number to fix wordpress.org issues
- support images in mp4chaps
- more valid/better js code
- save playtime in cookies

= 2.0.11 =
- empty chapter file and empty meta_box bug fixed
- chapter images added to chapter table
- chapter links added to chapter table
- chapter table bugfix
- max chapter table height changeable
- buttons improved (style and size)

= 2.0.10 =
- wordpress.org has some problems with the last commit
- sorry for the inconvenience

= 2.0.9 =
- sorry for the mp4 chaps bug
- now it’s working again

= 2.0.8 =
- better compatibility
- resume at last position
- build script (less requests)
- accept chapters as json-file

= 2.0.7 =
- Download bar added
- Button config added
- PHP Warnings removed
- various small changes

= 2.0.6 =
- podPress compatibility
- chapterbox height fix
- summary height fix
- infobutton style fixes
- jshint and jslint valid
- various small fixes
- Chapter hand over via JSON

= 2.0.5 =
- fixed Blubrry PowerPress compatibility
- fixed style Interference with various WP Themes
- firefox flash fallback multiple playing fix
- opera font bug fix
- more stable CSS

= 2.0.4 =
- fixed flash fallback again
- parameter handover improved
- encoding issues fixed

= 2.0.3 =
- reduced DOM interaction at player creation
- improved readability of JS code
- improved JS performance
- fixed video
- fixed flash fallback
- fixed player slowing down Firefox
- fixed buttons not being displayed properly
- added a new bar with social sharing buttons
- updated submodules

= 2.0.2 =
- equivalent to 2.0.1

= 2.0.1 =
- does not crash in PHP 5.2 anymore
- some CSS improvements for responsive layouts
- fixes visual glitches in readme.txt

= 2.0.0 =
- refactored large parts of the code
- added standalone player, works without PHP (example HTML/JS included)
- moved lots of functionality from PHP to JS
- cleaned variables and removed old stuff
- CSS improvements
- new settings area (yes, again. But now WordPress API compliant)
- added FontAwesome for fancy control buttons
- added “duration” parameter for displaying duration of last chapter
- added “permalink” parameter
- added “alwaysShowHours” parameter
- added “alwaysShowControls” parameter
- added “chaptersVisible” parameter
- added “timecontrolsVisible” parameter
- added “summaryVisible” parameter
- added sample audio files for testing purposes
- fresh versions of mediaelementjs and jQuery

= 1.2 =
- added: Rich player with meta information (title, subtitle, summary, cover image)
- added: Opus audio codec support
- added: Chapter duration display
- added: Chapter deeplinking
- added: optional listening to WordPress enclosures
- new settings area
- fixed some issues with flash fallback
- freshest version of mediaelement.js
- lots of bugfixes and improvements

= 1.1.2 =
- prevents activation conflicts with other instances of the plugin

= 1.1.1 =
- small bugfixes and improvements

= 1.1 =
- First proper release.
- [audio] and [video] are deprecated: Use [podloveaudio] and [podlovevideo] instead!
- Implements W3C Media Fragements with start and end time

= 1.0 =
- First version on wordpress.org
- Full of bugs
