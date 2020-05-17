import router from '../router'
import { request } from '../lib'

export default {
  namespaced: true,

  state: {
    loaded: false
  },

  getters: {
    loaded(state) {
      return state.loaded
    }
  },

  actions: {
    bootstrap({ commit, dispatch }) {
      request
        .read(PODLOVE_WEB_PLAYER.api.bootstrap, {
          loading: PODLOVE_WEB_PLAYER.i18n.message_initializing,
          error: PODLOVE_WEB_PLAYER.i18n.error_load_config,
        })
        .then(data => {
          dispatch('preview/bootstrap', data, { root: true })
          dispatch('configs/bootstrap', data, { root: true })
          dispatch('themes/bootstrap', data, { root: true })
          dispatch('templates/bootstrap', data, { root: true })
          dispatch('presets/bootstrap', data, { root: true })
          dispatch('settings/bootstrap', data, { root: true })
        })
        .finally(() => commit('loaded'))
    },

    create({ commit, rootGetters, dispatch }) {
      const target = rootGetters['modal/target']
      const id = rootGetters['modal/id']
      const blueprint = rootGetters['modal/blueprint']

      switch (target) {
        case 'config': {
          dispatch('configs/add', { id, blueprint }, { root: true }).then(() => {
            commit('modal/updateModalVisibility', { value: false, type: null, target: null }, { root: true })
            router.push({ name: 'config', params: { id } })
          })
          break
        }

        case 'theme': {
          dispatch('themes/add', { id, blueprint }, { root: true }).then(() => {
            commit('modal/updateModalVisibility', { value: false, type: null, target: null }, { root: true })
            router.push({ name: 'theme', params: { id } })
          })
          break
        }

        case 'template': {
          dispatch('templates/add', { id, blueprint }, { root: true }).then(() => {
            commit('modal/updateModalVisibility', { value: false, type: null, target: null }, { root: true })
            router.push({ name: 'template', params: { id } })
          })
          break
        }
      }
    },

    // Save Handling
    save({ rootGetters, dispatch }) {
      const type = rootGetters['router/name']

      switch (type) {
        case 'config': {
          dispatch('configs/save', null, { root: true })
          break
        }

        case 'theme': {
          dispatch('themes/save', null, { root: true })
          break
        }

        case 'template': {
          dispatch('templates/save', null, { root: true })
          break
        }

        case 'settings': {
          dispatch('settings/save', null, { root: true })
          break
        }
      }
    },

    // Delete
    remove({ commit, dispatch }, { target, id }) {
      switch (target) {
        case 'config': {
          dispatch('configs/remove', id, { root: true }).then(() => {
            commit('modal/updateModalVisibility', { value: false, type: null, target: null }, { root: true })
            router.push({ name: 'config', params: { id: 'default' } })
          })
          break
        }
        case 'theme': {
          dispatch('themes/remove', id, { root: true }).then(() => {
            commit('modal/updateModalVisibility', { value: false, type: null, target: null }, { root: true })
            router.push({ name: 'theme', params: { id: 'default' } })
          })
          break
        }
        case 'template': {
          dispatch('templates/remove', id, { root: true }).then(() => {
            commit('modal/updateModalVisibility', { value: false, type: null, target: null }, { root: true })
            router.push({ name: 'template', params: { id: 'default' } })
          })
          break
        }
      }
    }
  },

  mutations: {
    loaded (state) {
      state.loaded = true
    }
  }
}
