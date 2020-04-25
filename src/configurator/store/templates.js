import { get, unset, cloneDeep } from 'lodash'
import { request } from '../lib'

export default {
  namespaced: true,

  state: {
    templates: {},
  },

  getters: {
    templates(state) {
      return state.templates
    },

    templateList(state) {
      return Object.keys(state.templates)
    },

    isActive(state, getters, rootState, rootGetters) {
      return rootGetters['router/name'] === 'template'
    },

    id(state, getters, rootState, rootGetters) {
      const id = rootGetters['router/id']

      if (!getters.isActive || !id) {
        return null
      }

      return id
    },

    current(state, getters) {
      const id = getters.id

      if (!getters.id) {
        return {}
      }

      return get(getters.templates, id, '')
    },
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'templates'))
    },

    updateTemplate({ getters, commit }, value) {
      const id = getters.id

      if (!id) {
        return
      }

      commit('updateTemplate', { id, value })
    },

    add({ getters, commit }, id) {
      const template = get(getters.templates, 'default', {})
      request
        .create(
          `${PODLOVE_WEB_PLAYER.api.template}/${id}`,
          { template },
          {
            loading: PODLOVE_WEB_PLAYER.i18n.message_creating,
            error: PODLOVE_WEB_PLAYER.i18n.error_save_template,
          }
        )
        .then(template => {
          commit('updateTemplate', { id, template })
        })
    },

    save({ getters, commit }) {
      return request
        .create(
          `${PODLOVE_WEB_PLAYER.api.template}/${getters.id}`,
          { template: getters.current },
          {
            loading: PODLOVE_WEB_PLAYER.i18n.message_saving,
            error: PODLOVE_WEB_PLAYER.i18n.error_save_template,
          }
        )
        .then(value => {
          commit('updateTemplate', { id: getters.id, value })
        })
    },

    remove({ commit }, id) {
      return request
        .remove(`${PODLOVE_WEB_PLAYER.api.template}/${id}`, {
          loading: PODLOVE_WEB_PLAYER.i18n.message_saving,
          error: PODLOVE_WEB_PLAYER.i18n.error_delete_template,
        })
        .then(() => {
          commit('removeTemplate', { id })
        })
    },
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.templates = payload
    },

    updateTemplate(state, { id, value }) {
      state.templates = {
        ...state.templates,
        [id]: value,
      }
    },

    removeTemplate(state, { id }) {
      const data = cloneDeep(state.templates)
      unset(data, id)

      state.templates = data
    },
  },
}
