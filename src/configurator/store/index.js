import Vue from 'vue'
import Vuex from 'vuex'

import lifecycle from './lifecycle'
import router from './router'
import configs from './configs'
import themes from './themes'
import templates from './templates'
import settings from './settings'
import preview from './preview'
import modal from './modal'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    configs,
    themes,
    templates,
    settings,
    preview,
    modal,
    lifecycle,
    router
  }
})
