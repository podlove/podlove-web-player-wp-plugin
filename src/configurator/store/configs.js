import podcastClients from '@podlove/clients'
import { get, set, pick, unset, cloneDeep, uniq } from 'lodash'

import { request } from '../lib'

export default {
  namespaced: true,

  state: {
    configs: {},
    tabs: [null, 'shownotes', 'chapters', 'transcripts', 'share', 'files', 'playlist'],
    channels: ['facebook', 'twitter', 'whats-app', 'linkedin', 'pinterest', 'xing', 'mail', 'link'],
    stagedClient: {
      id: null,
    },
    clients: Object.values(
      podcastClients().reduce((result, item) => {
        const existing = get(result, item.id, {})

        return {
          ...result,
          [item.id]: {
            id: item.id,
            icon: item.icon,
            platforms: [...(existing.platforms ? existing.platforms : []), item.platform],
            title: item.title,
            serviceScheme: existing.serviceScheme || item.type === 'service' ? item.scheme : null,
            service: null,
          },
        }
      }, {})
    ),
  },

  getters: {
    configs(state) {
      return state.configs
    },

    configList(state) {
      return Object.keys(state.configs)
    },

    isActive(state, getters, rootState, rootGetters) {
      return rootGetters['router/name'] === 'config'
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

      return get(getters.configs, id, {})
    },

    tabs(state) {
      return state.tabs
    },

    channels(state) {
      return state.channels
    },

    clients(state) {
      return state.clients
    },

    stagedClient(state) {
      return state.stagedClient
    },

    relatedEpisodes(state, getters) {
      const current = getters.current

      return get(current, 'related-episodes', {
        source: 'podcast',
        value: 25
      })
    }
  },

  actions: {
    bootstrap({ commit }, payload) {
      commit('bootstrap', get(payload, 'configs'))
    },

    updateChannels({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      commit('updateChannels', { id: getters.id, channels: payload })
    },

    removeChannel({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      const channels = get(getters.current, ['share', 'channels'], []).filter(channel => channel !== payload)

      commit('updateChannels', { id: getters.id, channels })
    },

    addChannel({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      const channels = get(getters.current, ['share', 'channels'], [])

      commit('updateChannels', { id: getters.id, channels: [payload, ...channels] })
    },

    updateSharePlaytime({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      commit('updateSharePlaytime', { id: getters.id, sharePlaytime: payload })
    },

    updateEmbedPlayer({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      commit('updateEmbedPlayer', { id: getters.id, embed: payload })
    },

    addClient({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      const clients = get(getters.current, ['subscribe-button', 'clients'], [])

      commit('updateClients', { id: getters.id, clients: [pick(payload, ['id', 'service']), ...clients] })
    },

    removeClient({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }

      const stagedClient = getters.stagedClient
      const clients = get(getters.current, ['subscribe-button', 'clients'], []).filter(
        client => client.id !== payload.id
      )
      commit('updateClients', { id: getters.id, clients })

      if (stagedClient.id === payload.id) {
        commit('stageClient', { id: null })
      }
    },

    updateClients({ getters, commit }, payload = []) {
      if (!getters.id) {
        return
      }

      commit('updateClients', { id: getters.id, clients: payload.map(client => pick(client, ['id', 'service'])) })
    },

    updateClientService({ getters, commit }, payload) {
      if (!getters.id) {
        return
      }
      const stagedClient = getters.stagedClient
      const updatedClient = {
        ...stagedClient,
        service: payload,
      }
      commit('stageClient', updatedClient)

      const clients = get(getters.current, ['subscribe-button', 'clients'], []).map(client =>
        client.id === updatedClient.id ? updatedClient : client
      )

      commit('updateClients', { id: getters.id, clients: clients.map(client => pick(client, ['id', 'service'])) })
    },

    stageClient({ getters, commit }, client) {
      if (!getters.id || !client.serviceScheme) {
        return
      }

      commit('stageClient', client)
    },

    updateFeed({ getters, commit }, feed) {
      if (!getters.id) {
        return
      }

      commit('updateFeed', { id: getters.id, feed })
    },

    selectActiveTab({ getters, commit }, tab) {
      if (!getters.id) {
        return
      }

      commit('setActiveTab', { id: getters.id, tab })
    },

    updateSource({ getters, commit }, source) {
      if (!getters.id) {
        return
      }

      commit('updateSource', { id: getters.id, source })
    },

    updateSourceShow({ getters, commit }, show) {
      if (!getters.id) {
        return
      }

      commit('updateSourceShow', { id: getters.id, show })
    },

    save({ getters, commit }) {
      if (!getters.id) {
        return
      }
      request
        .create(`${PODLOVE_WEB_PLAYER.api.config}/${getters.id}`, getters.current, {
          loading: PODLOVE_WEB_PLAYER.i18n.message_saving,
          error: PODLOVE_WEB_PLAYER.i18n.error_save_config,
        })
        .then(config => {
          commit('updateConfig', { id: getters.id, config })
        })
    },

    add({ rootGetters, commit }, { id, blueprint }) {
      const copy = rootGetters['presets/item']('configs', blueprint)

      return request
        .create(`${PODLOVE_WEB_PLAYER.api.config}/${id}`, copy, {
          loading: PODLOVE_WEB_PLAYER.i18n.message_creating,
          error: PODLOVE_WEB_PLAYER.i18n.error_save_config
        })
        .then(config => {
          commit("updateConfig", { id, config });
        })
    },

    remove({ commit }, id) {
      return request
        .remove(`${PODLOVE_WEB_PLAYER.api.config}/${id}`, {
          loading: PODLOVE_WEB_PLAYER.i18n.message_saving,
          error: PODLOVE_WEB_PLAYER.i18n.error_delete_config,
        })
        .then(() => {
          commit('removeConfig', { id })
        })
    }
  },

  mutations: {
    bootstrap(state, payload = {}) {
      state.configs = payload
    },

    updateChannels(state, { id, channels }) {
      set(state, ['configs', id, 'share', 'channels'], uniq(channels))
    },

    updateSharePlaytime(state, { id, sharePlaytime }) {
      set(state, ['configs', id, 'share', 'sharePlaytime'], sharePlaytime)
    },

    updateEmbedPlayer(state, { id, embed }) {
      set(state, ['configs', id, 'share', 'outlet'], embed ? '/share.html' : null)
    },

    updateClients(state, { id, clients }) {
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

    updateConfig(state, { id, config }) {
      state.configs = {
        ...state.configs,
        [id]: config
      }
    },

    removeConfig(state, { id }) {
      const data = cloneDeep(state.configs)
      unset(data, id)

      state.configs = data
    },

    updateSource(state, { id, source }) {
      set(state, ['configs', id, 'related-episodes', 'source'], source)
      set(state, ['configs', id, 'related-episodes', 'value'], null)
    },

    updateSourceShow(state, { id, show }) {
      set(state, ['configs', id, 'related-episodes', 'value'], show)
    }
  },
}
