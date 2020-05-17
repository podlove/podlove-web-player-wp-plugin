import { get } from 'lodash'
import { get as select, compose, keys } from 'lodash/fp'

export default {
  namespaced: true,

  state: {
    configs: {},
    themes: {},
    templates: {},
  },

  getters: {
    configs: compose(keys, select('configs')),
    themes: compose(keys, select('themes')),
    templates: compose(keys, select('templates')),
    item: state => (type, id) => get(state, [type, id])
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'presets'))
    },
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.configs = get(payload, 'configs', {})
      state.themes = get(payload, 'themes', {})
      state.templates = get(payload, 'templates', {})
    },
  },
}
