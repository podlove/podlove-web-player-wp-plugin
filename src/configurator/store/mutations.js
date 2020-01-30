import { set, head, keys, get, cloneDeep, unset } from 'lodash'
import getters from './getters'

export default {
  loaded (state) {
    state.loaded = true
  },

  setState (state, payload = {}) {
    const screen = get(state, 'router.name')
    const routeId = get(state, 'router.params.id')

    state.templates = getters.templates(payload) || getters.templates(state)
    state.settings = getters.settings(payload) || getters.settings(state)

    state.preview.config = screen === 'config' ? routeId : (state.preview.config || head(keys(state.configs)))
    state.preview.theme = screen ===  'theme' ? routeId : (state.preview.theme || head(keys(state.themes)))
    state.preview.template = screen ===  'template' ? routeId : (state.preview.template || head(keys(state.templates)))
  },

  setPreviewOption(state, { option, value }) {
    state.preview[option] = value
  },

  // Modal
  updateModalValue(state, { value }) {
    let existing = []
    let error = false

    switch (state.modal.type) {
      case 'config':
        existing = keys(state.configs) || []
        break;
      case 'theme':
        existing = keys(state.themes) || []
        break;
      case 'template':
        existing = keys(state.templates) || []
        break;
    }

    if (existing.includes(value)) {
      state.modal.error = 'Id already exists'
      error = true
    }

    if (!/^[a-z]+$/.test(value)) {
      state.modal.error = 'Only lower cased characters are allowed'
      error = true
    }

    if (!error) {
      state.modal.error = null
    }

    state.modal.value = value
  },

  updateModalVisibility(state, { value, type, target, id }) {
    state.modal.visible = value
    state.modal.error = null
    state.modal.value = null
    state.modal.type = type
    state.modal.id = id
    state.modal.target = target
  },

  // Updates
  updateTemplate(state, { id, template }) {
    state.templates = {
      ...state.templates,
      [id]: template
    }
  },

  updateSource(state, source) {
    set(state, 'settings.source.selected', source)
  },

  updateSettings(state, settings) {
    state.settings = settings
  },


  // Remove
  removeTemplate(state, { id }) {
    const data = cloneDeep(state.templates)
    unset(data, id)

    state.templates = data
  }
}
