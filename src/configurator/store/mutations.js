import { set, head, keys, get } from 'lodash'
import getters from './getters'

export default {
  loaded (state) {
    state.loaded = true
  },

  setState (state, payload = {}) {
    const screen = get(state, 'router.name')
    const routeId = get(state, 'router.params.id')

    state.configs = getters.configs(payload) || getters.configs(state)
    state.themes = getters.themes(payload) || getters.themes(state)
    state.templates = getters.templates(payload) || getters.templates(state)

    state.preview.config = screen === 'config' ? routeId : (state.preview.config || head(keys(state.configs)))
    state.preview.theme = screen ===  'theme' ? routeId : (state.preview.theme || head(keys(state.themes)))
    state.preview.template = screen ===  'template' ? routeId : (state.preview.template || head(keys(state.templates)))
  },

  updateChannels (state, { id, channels }) {
    set(state, ['configs', id, 'share', 'channels'], channels)
  },

  updateSharePlaytime (state, { id, sharePlaytime }) {
    set(state, ['configs', id, 'share', 'sharePlaytime'], sharePlaytime)
  },

  updateEmbedPlayer (state, { id, embed }) {
    set(state, ['configs', id, 'share', 'outlet'], embed ? '/share.html' : null)
  },

  updateClients (state, { id, clients }) {
    set(state, ['configs', id, 'subscribe-button', 'clients'], clients)
  },

  stageClient(state, client) {
    state.stagedClient = client
  },

  updateFeed(state, { id, feed }) {
    set(state, ['configs', id, 'subscribe-button', 'feed'], feed)
  },

  setActiveTab(state, { id, tab }) {
    set(state, ['configs', id, 'activeTab'], tab)
  },

  setPreviewOption(state, { option, value }) {
    state.preview[option] = value
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

  updateTemplate(state, { id, value }) {
    set(state, ['templates', id], value)
  }
}
