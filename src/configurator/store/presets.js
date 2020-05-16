import { get } from 'lodash'

export default {
  namespaced: true,

  state: {
    configs: {},
    themes: {},
    templates: {}
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'presets'))
    }
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.configs = get(payload, 'configs', {})
      state.themes = get(payload, 'themes', {})
      state.templates = get(payload, 'templates', {})
    },
  }
}
