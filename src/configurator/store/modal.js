export default {
  namespaced: true,

  state: {
    visible: false,
    type: null,
    target: null,
    value: null,
    error: null,
    id: null,
  },

  getters: {
    visible(state) {
      return state.visible
    },

    target(state) {
      return state.target
    },

    type(state) {
      return state.type
    },

    value(state) {
      return state.value
    },

    error(state) {
      return state.error
    },

    id(state) {
      return state.id
    },
  },

  actions: {
    updateCreateModalValue({ commit, getters, rootGetters }, value) {
      let existing

      switch (getters.target) {
        case 'config':
          existing = rootGetters['configs/configList'] || []
          break
        case 'theme':
          existing = rootGetters['themes/themeList'] || []
          break
        case 'template':
          existing = rootGetters['templates/templateList'] || []
          break
      }

      commit('updateModalValue', { value, existing })
    },

    closeModal({ commit }) {
      commit('updateModalVisibility', { value: false, type: null, target: null })
    },

    showCreateModal({ commit }, target) {
      commit('updateModalVisibility', { value: true, target, type: 'create' })
    },

    // Delete Modal
    showDeleteModal({ commit }, { target, id }) {
      commit('updateModalVisibility', { value: true, target, type: 'delete', id })
    },
  },

  mutations: {
    // Modal
    updateModalValue(state, { value, existing }) {
      let error = false

      if (existing.includes(value)) {
        state.error = window.PODLOVE_WEB_PLAYER.i18n.modal['id-exists']
        error = true
      }

      if (!/^[a-z]+$/.test(value)) {
        state.error = window.PODLOVE_WEB_PLAYER.i18n.modal['id-invalid']
        error = true
      }

      if (!error) {
        state.error = null
      }

      state.value = value
    },

    updateModalVisibility(state, { value, type, target, id }) {
      state.visible = value
      state.error = null
      state.value = null
      state.type = type
      state.id = id
      state.target = target
    },
  },
}
