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

    save({ getters, commit }) {
      const source = get(getters.settings, 'source.selected')

        request
          .create(
            PODLOVE.api.settings,
            { source },
            {
              loading: PODLOVE.i18n.message_saving,
              error: PODLOVE.i18n.error_save_settings,
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
    },

    updateSource(state, source) {
      set(state, 'source.selected', source)
    },

    updateSettings(state, settings) {
      state.settings = settings
    },
  },
}
