import { compose, get, keys } from 'lodash/fp'

const slices = {
  configs: get('configs'),
  themes: get('themes'),
  templates: get('templates'),
  episode: get('episode'),
  settings: get('settings'),
  router: get('route'),
  clients: get('clients'),
  settings: get('settings')
}

const routeId = compose(get('id'), get('params'), slices.router)

export default {
  ...slices,
  loaded: get('loaded'),
  modal: get('modal'),
  channels: get('channels'),
  tabs: get('tabs'),
  stagedClient: get('stagedClient'),
  fonts: get('fonts'),
  configList: compose(keys, slices.configs),
  themeList: compose(keys, slices.themes),
  templateList: compose(keys, slices.templates),
  routeName: compose(get('name'), slices.router),
  preview: get('preview'),
  routeId
}
