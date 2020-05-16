import { head, get } from 'lodash'

export default {
  namespaced: true,

  state: {
    config: null,
    theme: null,
    template: null,
    size: 'desktop',
    contentWidth: null
  },

  getters: {
    preview(state) {
      return state
    },

    config(state) {
      return state.config
    },

    theme(state) {
      return state.theme
    },

    template(state) {
      return state.template
    },

    contentWidth(state) {
      return state.contentWidth
    }
  },

  actions: {
    bootstrap({ commit, getters, rootGetters }, data) {
      const name = rootGetters['router/name']
      const id = rootGetters['router/id']
      const contentWidth = get(data, 'settings.contentWidth')
      const defaultSettings = get(data, 'settings.defaults')

      commit('setPreviewOption', { option: 'config', value: name === 'config' ? id : (getters.config || get(defaultSettings, 'config')) })
      commit('setPreviewOption', { option: 'theme', value: name === 'theme' ? id : (getters.theme || get(defaultSettings, 'theme')) })
      commit('setPreviewOption', { option: 'template', value: name === 'config' ? id : (getters.template || get(defaultSettings, 'template')) })
      commit('setPreviewOption', { option: 'contentWidth', value: contentWidth })

      if (contentWidth) {
        commit('setPreviewOption', { option: 'size', value: 'content' })
      }
    },

    updatePreviewOption({ commit }, { option, value }) {
      commit('setPreviewOption', { option, value })
    },
  },

  mutations: {
    setPreviewOption(state, { option, value }) {
      state[option] = value
    }
  }
}
