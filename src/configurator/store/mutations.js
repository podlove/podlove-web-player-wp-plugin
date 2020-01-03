import getters from './getters'

export default {
  loaded (state) {
    state.loaded = true
  },

  setState (state, payload = {}) {
    state.configs = getters.configs(payload) || getters.configs(state)
    state.themes = getters.themes(payload) || getters.themes(state)
    state.templates = getters.templates(payload) || getters.templates(state)
  }
}
