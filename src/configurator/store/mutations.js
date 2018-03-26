import { reduce, cloneDeep, noop } from 'lodash'

import { config } from './state'

const defaultConfig = cloneDeep(config)

export default {
  loaded (state) {
    state.loaded = true
  },

  setTheme (state, theme) {
    state.config.theme.main = theme.main || defaultConfig.theme.main
    state.config.theme.highlight = theme.highlight || defaultConfig.theme.highlight
  },

  setComponent (state, { component, visible }) {
    state.config.visibleComponents[component] = visible
  },

  setShow (state, show) {
    state.config.show = {
      ...state.config.show,
      ...show
    }
  },

  setActiveTab (state, tab) {
    state.config.tabs = reduce(defaultConfig.tabs, (result, active, name) => ({
      ...result,
      [name]: name === tab
    }), {})
  },

  setState (state, config = {}) {
    // Theme
    state.config.theme = config.theme

    // Poster
    state.config.show = {
      ...state.config.show,
      ...config.show
    }

    // Visible Components
    Object.keys(defaultConfig.visibleComponents).forEach(key => {
      state.config.visibleComponents[key] = config.visibleComponents.indexOf(key) !== -1
    })

    // Tabs
    state.config.tabs = config.tabs

    // Enclosure
    state.config.enclosure = config.enclosure
  },

  setEnclosure (state, { key, value }) {
    state.config.enclosure[key] = value
  },

  // fake mutations to use for plugin
  simulateTheme: noop
}
