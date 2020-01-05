import { set } from 'lodash'
import getters from './getters'

export default {
  loaded (state) {
    state.loaded = true
  },

  setState (state, payload = {}) {
    state.configs = getters.configs(payload) || getters.configs(state)
    state.themes = getters.themes(payload) || getters.themes(state)
    state.templates = getters.templates(payload) || getters.templates(state)
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
  }
}
