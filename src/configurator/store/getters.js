import { get } from 'lodash/fp'

import { config } from './state'

export default {
  config: get('config'),
  meta: get('meta'),
  theme: get('config.theme'),
  tabs: get('config.tabs'),
  enclosure: get('config.enclosure'),
  visibleComponents: state => Object.keys(config.visibleComponents).filter(key => state.config.visibleComponents[key])
}
