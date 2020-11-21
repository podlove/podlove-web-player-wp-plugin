import { get, unset, cloneDeep } from 'lodash'
import { request } from '../lib'

export default {
  namespaced: true,

  state: {
    shows: [],
  },

  getters: {
    shows(state) {
      return state.shows
    },
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'shows'))
    }
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.shows = payload
    }
  },
}
