/* global PODLOVE */

import Vue from 'vue'
import { get } from 'lodash'
import { Loading, Notification } from 'element-ui'

import { config } from './state'

export default {
  boot ({ commit }) {
    const initializing = Loading.service({ fullscreen: true, text: PODLOVE.i18n.message_initializing })

    Vue.http.get(PODLOVE.api)
      .then(({ data }) => commit('setState', data))
      .catch(err => {
        err.json().then(result => {
          Notification.error({
            offset: 30,
            title: PODLOVE.i18n.error_load_config,
            message: get(result, 'message', result)
          })
        })
      })
      .finally(() => {
        initializing.close()
        commit('loaded')
      })
  },

  save ({ getters }) {
    const saving = Loading.service({ fullscreen: true, text: PODLOVE.i18n.message_saving })
    const visibleComponents = get(getters, 'visibleComponents', config.visibleComponents)
    const enclosure = get(getters, 'enclosure', config.enclosure)
    const tabs = get(getters, 'tabs', config.tabs)
    const theme = get(getters, 'theme', config.theme)
    const show = get(getters, 'show', config.show)

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
