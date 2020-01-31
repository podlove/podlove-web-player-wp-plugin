/* global PODLOVE */
import { get, pick, reduce } from 'lodash'
import { request } from '../lib'
import router from '../router'

export default {
  bootstrap({ commit, dispatch }) {
    request
      .read(PODLOVE.api.bootstrap, {
        loading: PODLOVE.i18n.message_initializing,
        error: PODLOVE.i18n.error_load_config,
      })
      .then(data => {
        dispatch('preview/bootstrap', data)
        dispatch('configs/bootstrap', data)
        dispatch('themes/bootstrap', data)
        dispatch('templates/bootstrap', data)
        dispatch('settings/bootstrap', data)
      })
      .finally(() => commit('loaded'))
  },

  create({ commit, getters, dispatch }) {
    const target = getters.modal.target
    const id = getters.modal.value

    switch (target) {
      case 'config': {
        dispatch('configs/add', id).then(() => {
          commit('modal/updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'config', params: { id } })
        })
        break
      }

      case 'theme': {
        dispatch('themes/add', id).then(() => {
          commit('modal/updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'theme', params: { id } })
        })
        break
      }

      case 'template': {
        dispatch('templates/add', id).then(() => {
          commit('modal/updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'template', params: { id } })
        })
        break
      }
    }
  },

  // Save Handling
  save({ getters, dispatch }) {
    const type = getters.routeName

    switch (type) {
      case 'config': {
        dispatch('configs/save')
        break
      }

      case 'theme': {
        dispatch('themes/save')
        break
      }

      case 'template': {
        dispatch('templates/save')
        break
      }

      case 'settings': {
        dispatch('settings/save')
        break
      }
    }
  },

  // Delete
  remove({ commit, dispatch }, { target, id }) {
    switch (target) {
      case 'config': {
        dispatch('configs/remove', id).then(() => {
          commit('modal/updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'config', params: { id: 'default' } })
        })
        break
      }
      case 'theme': {
        dispatch('themes/remove', id).then(() => {
          commit('modal/updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'theme', params: { id: 'default' } })
        })
        break
      }
      case 'template': {
        dispatch('templates/remove', id).then(() => {
          commit('modal/updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'template', params: { id: 'default' } })
        })
        break
      }
    }
  }
}
