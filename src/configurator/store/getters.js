import { compose, get, keys } from 'lodash/fp'

const slices = {
  configs: get('configs'),
  themes: get('themes'),
  templates: get('templates'),
  episode: get('episode'),
  settings: get('settings'),
  router: get('route')
}

export default {
  ...slices,
  loaded: get('loaded'),
  configList: compose(keys, slices.configs),
  themeList: compose(keys, slices.themes),
  templateList: compose(keys, slices.templates),
  routeName: compose(get('name'), slices.router),
  routeId: compose(get('id'), get('params'), slices.router)
}
