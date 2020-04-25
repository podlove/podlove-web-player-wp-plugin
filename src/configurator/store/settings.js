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
    legacy: null
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

    save({ getters, commit }) {
      const source = get(getters.settings, 'source.selected')
      const enclosure = getters.enclosure
      const legacy = getters.legacy

        request
          .create(
            PODLOVE_WEB_PLAYER.api.settings,
            { source, enclosure, legacy },
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
      state.source = payload.source
      state.enclosure = payload.enclosure
      state.legacy = payload.legacy
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
    }
  },
}
