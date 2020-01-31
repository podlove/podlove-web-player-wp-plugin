import Vue from 'vue'
import Vuex from 'vuex'
import { cloneDeep } from 'lodash'

import configs from './configs'
import themes from './themes'
import templates from './templates'
import settings from './settings'
import preview from './preview'
import modal from './modal'

import state from './state'
import getters from './getters'
import actions from './actions'
import mutations from './mutations'
import plugins from './plugins'

Vue.use(Vuex)

export default new Vuex.Store({
  state,
  getters: cloneDeep(getters),
  actions,
  mutations,
  plugins,

  modules: {
    configs,
    themes,
    templates,
    settings,
    preview,
    modal
  }
})
