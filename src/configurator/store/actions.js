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
      })
      .finally(() => commit('loaded'))
  },

  // Template
  updateTemplate({ getters, commit }, value) {
    if (getters.routeName !== 'template') {
      return
    }

    commit('updateTemplate', { id: getters.routeId, value })
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
        const template = get(getters.templates, 'default', {})

        request
          .create(
            `${PODLOVE.api.template}/${id}`,
            { template },
            {
              loading: PODLOVE.i18n.message_creating,
              error: PODLOVE.i18n.error_save_template,
            }
          )
          .catch(console.warn)
          .then(template => {
            commit('updateTemplate', { id, template })
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
        const payload = get(getters.templates, id, {})

        request
          .create(`${PODLOVE.api.template}/${id}`, payload, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_save_template,
          })
          .catch(console.warn)
          .then(template => {
            commit('updateTemplate', { id, template })
          })

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
          .catch(console.warn)
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
        request
          .remove(`${PODLOVE.api.template}/${id}`, {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_delete_template,
          })
          .catch(console.warn)
          .then(() => {
            commit('removeTemplate', { id })
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
