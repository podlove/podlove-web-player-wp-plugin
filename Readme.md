# Podlove Web Player 4 Wordpress Plugin

## Requirements

- NodeJS 8
- Yarn

## Bootstrap

```bash
$ yarn install
```

## Build

```bash
$ yarn build
```

You will find all plugin assets in `/dist`

## Development

To develop the Wordpress Plugin a dedicated wordress environment can be created by running:

```bash
$ ./wordpress dev init # bootstraps a dockerized wordpress, see compose files in /docker
$ ./yarn dev # file watchers that copies the php and javascript sources 
```