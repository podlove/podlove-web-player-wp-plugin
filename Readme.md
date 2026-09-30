# Podlove Web Player 5 Wordpress Plugin

## Requirements

- Node >= 12

## Bootstrap

```bash
$ npm install
```

## Build

```bash
$ npm run build
```

You will find all plugin assets in `/dist`

## Development

Use Node 20.18.1 (see `devbox.lock`) or a supported newer version, npm 11.18.0 or newer, and start Docker Desktop.

The project `.npmrc` enforces a 14-day cooldown for new package releases.
The engine check requires npm 11.18.0 or newer for the release-age and audit-fix behavior used here.
Sass and two release-tool dependencies are pinned to releases compatible with Node 20.18.1.

Build the plugin before starting WordPress:

```bash
npm install
npm run build
```

Create a local `.wp-env.json` in this directory:

```json
{
  "plugins": ["./dist"],
  "port": 8890,
  "testsEnvironment": false,
  "config": {
    "WP_DEBUG": true,
    "WP_DEBUG_LOG": true,
    "WP_DEBUG_DISPLAY": false
  }
}
```

Start WordPress:

```bash
npx --package=@wordpress/env wp-env start
```

Open http://localhost:8890/wp-admin and log in with `admin` / `password`.
The built plugin in `dist/` is mounted and activated automatically. Add a local
Publisher checkout to `plugins` if you need to test Publisher integration.

In another terminal, start the PHP, JavaScript, and CSS watchers:

```bash
npm run dev
```

Edit `src/block/` for the block editor and `src/plugin/` for PHP. Refresh the
browser after compilation. Block CSS is compiled separately and registered as
an editor stylesheet so WordPress loads it in the editor iframe and sidebar.
Restart `npm run dev` after changing the watcher scripts in `package.json`.

Stop the watchers with Ctrl+C. Stop WordPress without deleting its data:

```bash
npx --package=@wordpress/env wp-env stop
```

The legacy files in `docker/` target WordPress 5.4 and are unsuitable for the
Block API v3 migration. The plugin now requires WordPress 6.3 or newer.

## Verification

```bash
npm run test:block
npm run build
```

In the iframe editor, check new and existing blocks, Post/Publisher/Custom
sources, settings changes, multiple previews, block duplication/removal, and
saving/reopening posts. Existing blocks remain dynamically rendered with the
same name and attributes, so their saved content needs no conversion.
