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
      const screen = rootGetters.routeName
      const routeId = rootGetters.routeId

      commit('setPreviewOption', { option: 'config', value: screen === 'config' ? routeId : (getters.config || head(rootGetters['configs/configList'])) })
      commit('setPreviewOption', { option: 'theme', value: screen === 'theme' ? routeId : (getters.theme || head(rootGetters['themes/themeList'])) })
      commit('setPreviewOption', { option: 'template', value: screen === 'config' ? routeId : (getters.template || head(rootGetters['templates/templateList'])) })
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
