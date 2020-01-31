import { head } from 'lodash'

export default {
  namespaced: true,

  state: {
    config: 'default',
    theme: 'default',
    template: 'default',
    size: 'desktop'
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
  },

  actions: {
    bootstrap({ commit, getters, rootGetters }) {
      const name = rootGetters['router/name']
      const id = rootGetters['router/id']

      commit('setPreviewOption', { option: 'config', value: name === 'config' ? id : (getters.config || head(rootGetters['configs/configList'])) })
      commit('setPreviewOption', { option: 'theme', value: name === 'theme' ? id : (getters.theme || head(rootGetters['themes/themeList'])) })
      commit('setPreviewOption', { option: 'template', value: name === 'config' ? id : (getters.template || head(rootGetters['templates/templateList'])) })
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
