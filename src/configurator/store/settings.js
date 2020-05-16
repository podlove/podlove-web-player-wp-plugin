import { get, reduce, set } from 'lodash'
import { request } from '../lib'

export default {
  namespaced: true,

  state: {
    source: {
      selected: null,
      items: {
        cdn: null,
        local: null,
      },
    },
    enclosure: null,
    legacy: null,
    defaults: {
      theme: null,
      template: null,
      config: null
    }
  },

  getters: {
    settings(state) {
      return state
    },

    source(state) {
      const data = get(state, 'source')
      const items = get(data, 'items')
      const selected = get(data, 'selected')

      return get(items, selected)
    },

    enclosure(state) {
      return state.enclosure
    },

    legacy(state) {
      return state.legacy
    },

    defaults(state) {
      return state.defaults
    }
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'settings'))
    },

    updateSource({ commit, getters }, value) {
      const source = reduce(
        get(getters.settings, 'source.items', {}),
        (result, item, key) => (item === value ? key : result),
        null
      )

      commit('updateSource', source)
    },

    updateEnclosure({ commit }, value) {
      commit('updateEnclosure', value)
    },

    updateLegacy({ commit }, value) {
      commit('updateLegacy', value)
    },

    updateDefault({ commit }, { type, value }) {
      switch (type) {
        case 'config':
          commit('updateDefaultConfig', value)
          break
        case 'theme':
          commit('updateDefaultTheme', value)
          break
        case 'template':
          commit('updateDefaultTemplate', value)
          break
      }
    },

    save({ getters, commit }) {
      const source = get(getters.settings, 'source.selected')
      const enclosure = getters.enclosure
      const legacy = getters.legacy
      const defaults = getters.defaults

        request
          .create(
            PODLOVE_WEB_PLAYER.api.settings,
            { source, enclosure, legacy, defaults },
            {
              loading: PODLOVE_WEB_PLAYER.i18n.message_saving,
              error: PODLOVE_WEB_PLAYER.i18n.error_save_settings,
            }
          )
          .then(settings => {
            commit('updateSettings', settings)
          })
    }
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.source = get(payload, 'source', {})
      state.enclosure = get(payload, 'enclosure')
      state.legacy = get(payload, 'legacy', false),
      state.defaults  = get(payload, 'defaults', {})
    },

    updateSource(state, source) {
      set(state, 'source.selected', source)
    },

    updateEnclosure(state, enclosure) {
      set(state, 'enclosure', enclosure)
    },

    updateSettings(state, settings) {
      state.settings = settings
    },

    updateLegacy(state, legacy) {
      state.legacy = legacy
    },

    updateDefaultConfig(state, config) {
      state.defaults.config = config
    },

    updateDefaultTheme(state, theme) {
      state.defaults.theme = theme
    },

    updateDefaultTemplate(state, template) {
      state.defaults.template = template
    }
  },
}
