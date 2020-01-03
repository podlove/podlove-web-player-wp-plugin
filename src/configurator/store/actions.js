/* global PODLOVE */

import Vue from 'vue'
import { get } from 'lodash'
import { Loading, Notification } from 'element-ui'

import state from './state'

export default {
  boot ({ commit }) {
    const initializing = Loading.service({ fullscreen: true, text: PODLOVE.i18n.message_initializing })

    Vue.http.get(PODLOVE.api)
      .then(({ data }) => commit('setState', data))
      .catch(async err => {
        const error = err.json ? await err.json() : {}
        console.warn(err)

        Notification.error({
          offset: 30,
          title: PODLOVE.i18n.error_load_config,
          message: get(error, 'message')
        })
      })
      .finally(() => {
        initializing.close()
        commit('loaded')
      })
  },

  save ({ getters }) {
    Vue.http.post(PODLOVE.api, { visibleComponents, tabs, theme, enclosure, show })
    .catch(err => {
      err.json().then(result => {
        Notification.error({
          offset: 30,
          title: PODLOVE.i18n.error_save_config,
          message: get(result, 'message', err)
        })
      })
    })
    .finally(() => saving.close())
  },

  setTheme ({ commit }, theme) {
    commit('setTheme', theme)
  },

  simulateTheme ({ commit }, theme) {
    commit('simulateTheme', theme)
  },

  setComponent ({ commit }, data) {
    commit('setComponent', data)
  },

  setTab ({ commit }, tab) {
    commit('setActiveTab', tab)
  },

  setEnclosure ({ commit }, data) {
    commit('setEnclosure', data)
  },

  setShow ({ commit }, data) {
    commit('setShow', data)
  }
}
