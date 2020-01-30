/* global PODLOVE */
import { get, pick, reduce } from 'lodash'
import { request } from '../lib'
import router from '../router'

export default {
  bootstrap({ commit }) {
    request
      .read(PODLOVE.api.bootstrap, {
        loading: PODLOVE.i18n.message_initializing,
        error: PODLOVE.i18n.error_load_config,
      })
      .then(data => {
        commit('setState', data)
        commit('configs/bootstrap', get(data, 'configs'))
        commit('themes/bootstrap', get(data, 'themes'))
        commit('templates/bootstrap', get(data, 'templates'))
      })
      .finally(() => commit('loaded'))
  },

  // Preview
  updatePreviewOption({ commit }, { option, value }) {
    commit('setPreviewOption', { option, value })
  },

  // Create Modal
  updateCreateModalValue({ commit }, value) {
    commit('updateModalValue', { value })
  },

  closeModal({ commit }) {
    commit('updateModalVisibility', { value: false, type: null, target: null })
  },

  showCreateModal({ commit }, target) {
    commit('updateModalVisibility', { value: true, target, type: 'create' })
  },

  confirmCreateModal({ commit, getters, dispatch }) {
    const target = getters.modal.target
    const id = getters.modal.value

    switch (target) {
      case 'config': {
        dispatch('configs/add', id).then(() => {
          commit('updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'config', params: { id } })
        })
        break
      }

      case 'theme': {
        dispatch('themes/add', id).then(() => {
          commit('updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'theme', params: { id } })
        })
        break
      }

      case 'template': {
        dispatch('templates/add', id).then(() => {
          commit('updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'template', params: { id } })
        })
        break
      }
    }
  },

  // Delete Modal
  showDeleteModal({ commit }, { target, id }) {
    commit('updateModalVisibility', { value: true, target, type: 'delete', id })
  },

  // Save Handling
  save({ commit, getters, dispatch }) {
    const id = getters.routeId
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

        break
      }
    }
  },

  // Delete
  remove({ commit, dispatch }, { type, id }) {
    switch (type) {
      case 'config': {
        dispatch('configs/remove', id).then(() => {
          commit('updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'config', params: { id: 'default' } })
        })
        break
      }
      case 'theme': {
        dispatch('themes/remove', id).then(() => {
          commit('updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'theme', params: { id: 'default' } })
        })
        break
      }
      case 'template': {
        dispatch('templates/remove', id).then(() => {
          commit('updateModalVisibility', { value: false, type: null, target: null })
          router.push({ name: 'template', params: { id: 'default' } })
        })
        break
      }
    }
  },

  // Settings
  updateSource({ commit, getters }, value) {
    const source = reduce(
      get(getters.settings, 'source.items', {}),
      (result, item, key) => (item === value ? key : result),
      null
    )

    commit('updateSource', source)
  },
}
