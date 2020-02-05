# Podlove Web Player 4 Wordpress Plugin

## Requirements

- NodeJS 8
- Yarn

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

To develop the Wordpress Plugin a dedicated wordress environment can be created by running:

```bash
$ npm run dev:init # bootstraps a dockerized wordpress, see compose files in /docker
$ mpm run dev # file watchers that copies the php and javascript sources 
```

Afterwards you should be able to access the development instance at `http://localhost:8080`. If you need to change the route you can configure the development environment in a docker-compose file located at `docker/dev.yml`

To persist ad stop the current development state simply run:

```bash
$ npm run dev:save # persists database to database.sql and stops the docker environment
```

And finally if you need to shutdown the services run:

```bash
$ npm run dev:kill # simply stops the docker environment
```
