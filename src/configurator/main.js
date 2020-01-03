// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VueResource from 'vue-resource'
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'
import { sync } from 'vuex-router-sync'

import Configurator from './Configurator'
import store from './store'
import router from './router'

import { i18n } from './mixins'
Vue.config.productionTip = false

Vue.use(ElementUI, { locale })
Vue.use(VueResource)
Vue.mixin(i18n)

sync(store, router)

const configNode = document.getElementById('configurator')

/* eslint-disable no-new */
new Vue({
  el: configNode,
  store,
  router,
  template: '<Configurator />',
  components: { Configurator }
})
