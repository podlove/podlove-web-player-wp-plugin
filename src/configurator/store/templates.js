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
      return rootGetters.routeName === 'template'
    },

    id(state, getters, rootState, rootGetters) {
      if (!getters.isActive || !rootGetters.routeId) {
        return null
      }

      return rootGetters.routeId
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
    updateTemplate({ getters, commit }, value) {
      const id = getters.id

      if (!id) {
        return
      }

      commit('updateTemplate', { id, value })
    },

    add({ getters, commit }, id) {
      const template = get(getters.templates, 'default', {})
      console.log(template)
      request
        .create(
          `${PODLOVE.api.template}/${id}`,
          { template },
          {
            loading: PODLOVE.i18n.message_creating,
            error: PODLOVE.i18n.error_save_template,
          }
        )
        .then(template => {
          commit('updateTemplate', { id, template })
        })
    },

    save({ getters, commit }) {
      return request
        .create(
          `${PODLOVE.api.template}/${getters.id}`,
          { template: getters.current },
          {
            loading: PODLOVE.i18n.message_saving,
            error: PODLOVE.i18n.error_save_template,
          }
        )
        .then(template => {
          commit('updateTemplate', { id: getters.id, template })
        })
    },

    remove({ commit }, id) {
      return request
        .remove(`${PODLOVE.api.template}/${id}`, {
          loading: PODLOVE.i18n.message_saving,
          error: PODLOVE.i18n.error_delete_template,
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

    updateTemplate(state, { id, template }) {
      state.templates = {
        ...state.templates,
        [id]: template,
      }
    },

    removeTemplate(state, { id }) {
      const data = cloneDeep(state.templates)
      unset(data, id)

      state.templates = data
    },
  },
}
