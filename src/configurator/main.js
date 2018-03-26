// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import VueResource from 'vue-resource'
import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'

import Configurator from './Configurator'
import store from './store'

import { i18n } from './mixins'

Vue.config.productionTip = false

Vue.use(ElementUI, { locale })
Vue.use(VueResource)
Vue.mixin(i18n)

const configNode = document.getElementById('configurator')

/* eslint-disable no-new */
new Vue({
  el: configNode,
  store,
  template: '<Configurator />',
  components: { Configurator }
})
