import { get } from 'lodash'

export default {
  namespaced: true,

  getters: {
    id(state, getters, rootState) {
      return get(rootState, ['route', 'params', 'id'])
    },

    name(state, getters, rootState) {
      return get(rootState, ['route', 'name'])
    }
  }
}
