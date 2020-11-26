import Vue from 'vue'
import Vuesax from 'vuesax'
import { Datetime } from 'vue-datetime';
import api from './api/index'
import router from './router'
import store from './store/store'

import App from './apps/App.vue'

import 'vuesax/dist/vuesax.css' //Vuesax styles
import 'material-icons/iconfont/material-icons.css';
import 'boxicons'
import 'boxicons/css/boxicons.min.css'
import 'vue-datetime/dist/vue-datetime.css'

Vue.config.productionTip = false
Vue.config.devtools = true
Vue.use(Vuesax)
Vue.use(Datetime)
Vue.component('datetime', Datetime);
Vue.prototype.$api = api;

const app = new Vue({
  store,
  router,
  render: h => h(App),
}).$mount('#app')
import './assets/scss/style.scss'