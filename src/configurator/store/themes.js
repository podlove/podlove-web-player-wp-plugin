import { get, set, cloneDeep, unset } from 'lodash'
import { request } from '../lib'

export default {
  namespaced: true,

  state: {
    themes: {},
    fonts: {
      selected: 'ci',
      ci: {
        src: null,
        family: null,
        error: null,
      },
      regular: {
        src: null,
        family: null,
        error: null,
      },
      bold: {
        src: null,
        family: null,
        error: null,
      },
    },
  },

  getters: {
    themes(state) {
      return state.themes
    },

    themeList(state) {
      return Object.keys(state.themes)
    },

    fonts(state) {
      return state.fonts
    },

    isActive(state, getters, rootState, rootGetters) {
      return rootGetters['router/name'] === 'theme'
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

      return get(getters.themes, id, {})
    },
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'themes'))
    },

    updateToken({ getters, commit }, { token, color }) {
      if(!getters.isActive) {
        return
      }

      const tokens = get(getters.themes, [getters.id, 'tokens'], {})

      commit('updateTokens', {
        id: getters.id,
        tokens: {
          ...tokens,
          [token]: color,
        },
      })
    },

    selectFont({ getters, commit }, font) {
      if(!getters.isActive) {
        return
      }

      commit('selectFont', { font })
    },

    stageFontSource({ getters, commit }, { value }) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')

      commit('stageFontSource', {
        font,
        value,
      })
    },

    updateFontSource({ getters, commit }) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')
      const payload = get(getters.fonts, [font, 'src'])

      if (!payload) {
        return
      }

      commit('setFontSourceError', { font, value: payload })

      const error = get(getters.fonts, [font, 'error'])

      if (error) {
        return
      }

      const sources = get(getters.themes, [getters.id, 'fonts', font, 'src'], [])

      if (sources.includes(payload)) {
        return
      }

      commit('updateFontSource', { id: getters.id, font, value: [payload, ...sources] })
    },

    removeFontSrc({ getters, commit }, { value }) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')

      const sources = get(getters.themes, [getters.id, 'fonts', font, 'src'], []).filter(src => src !== value)

      commit('updateFontSource', { id: getters.id, font, value: sources })
    },

    updateFontWeight({ getters, commit }, { value }) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')

      commit('updateFontWeight', { id: getters.id, font, value })
    },

    addFontFamily({ getters, commit }) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')
      const payload = get(getters.fonts, [font, 'family'])

      if (!payload) {
        return
      }

      const family = get(getters.themes, [getters.id, 'fonts', font, 'family'], [])

      if (family.includes(payload)) {
        return
      }

      commit('updateFontFamily', { id: getters.id, font, value: [payload, ...family] })
    },

    stageFontFamily({ getters, commit }, { value }) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')

      commit('stageFontFamily', {
        font,
        value,
      })
    },

    updateFontFamily({ getters, commit }, value) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')

      commit('updateFontFamily', { id: getters.id, font, value })
    },

    removeFontFamily({ getters, commit }, value) {
      if(!getters.isActive) {
        return
      }

      const font = get(getters.fonts, ['selected'], 'ci')
      const family = get(getters.themes, [getters.id, 'fonts', font, 'family'], []).filter(item => item !== value)

      commit('updateFontFamily', { id: getters.id, font, value: family })
    },

    save({ getters, commit }) {
      return request
        .create(`${PODLOVE.api.theme}/${getters.id}`, getters.current, {
          loading: PODLOVE.i18n.message_saving,
          error: PODLOVE.i18n.error_save_theme,
        })
        .then(theme => {
          commit('updateTheme', { id: getters.id, theme })
        })
    },

    add({ getters, commit }, id) {
      const copy = get(getters.themes, 'default', {})

      return request
        .create(`${PODLOVE.api.theme}/${id}`, copy, {
          loading: PODLOVE.i18n.message_creating,
          error: PODLOVE.i18n.error_save_theme,
        })
        .then(theme => {
          commit('updateTheme', { id, theme })
        })
    },

    remove({ commit }, id) {
      return request
        .remove(`${PODLOVE.api.theme}/${id}`, {
          loading: PODLOVE.i18n.message_saving,
          error: PODLOVE.i18n.error_delete_theme,
        })
        .then(() => {
          commit('removeTheme', { id })
        })
    }
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.themes = payload
    },

    updateTokens(state, { id, tokens }) {
      set(state, ['themes', id, 'tokens'], tokens)
    },

    selectFont(state, { font }) {
      set(state, ['fonts', 'selected'], font)
    },

    stageFontSource(state, { font, value }) {
      set(state, ['fonts', font, 'src'], value)
    },

    setFontSourceError(state, { font, value }) {
      const eot = value.endsWith('.eot')
      const woff = value.endsWith('.woff')
      const woff2 = value.endsWith('.woff2')
      const ttf = value.endsWith('.ttf')
      const svg = value.endsWith('.svg')

      if (!eot && !woff && !woff2 && !ttf && !svg) {
        set(state, ['fonts', font, 'error'], 'Not a valid font source')
      } else {
        set(state, ['fonts', font, 'error'], null)
      }
    },

    updateFontSource(state, { id, font, value }) {
      set(state, ['themes', id, 'fonts', font, 'src'], value)
      set(state, ['fonts', font, 'src'], null)
    },

    updateFontWeight(state, { id, font, value }) {
      set(state, ['themes', id, 'fonts', font, 'weight'], value)
    },

    stageFontFamily(state, { font, value }) {
      set(state, ['fonts', font, 'family'], value)
    },

    updateFontFamily(state, { id, font, value }) {
      set(state, ['themes', id, 'fonts', font, 'family'], value)
      set(state, ['fonts', font, 'family'], null)
    },

    updateTheme(state, { id, theme }) {
      state.themes = {
        ...state.themes,
        [id]: theme
      }
    },

    removeTheme(state, { id }) {
      const data = cloneDeep(state.themes)
      unset(data, id)

      state.themes = data
    }
  },
}
