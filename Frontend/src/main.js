// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import Vuetify from 'vuetify'
import App from './App'
import store from './store'
import router from './router'

import colors from 'vuetify/es5/util/colors'

import '../node_modules/vuetify/dist/vuetify.min.css'

import {init} from './config';
init(store, router);

// set the color theme
Vue.use(Vuetify, {
  theme: {
    primary: colors.blueGrey.base,
    secondary: colors.green.base,
    accent: colors.cyan.base,
    error: colors.red.base,
    warning: colors.yellow.base,
    info: colors.blue.base,
    success: colors.green.base
  }
})

Vue.config.productionTip = false


/* eslint-disable no-new */
new Vue({
  el: '#app',
  router,
  store,
  components: { App },
  template: '<App/>'
})
