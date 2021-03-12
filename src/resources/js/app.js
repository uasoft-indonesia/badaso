import Vue from "vue";
import Vuesax from "vuesax";
import VueI18n from 'vue-i18n'
import { Datetime } from "vue-datetime";

import "vuesax/dist/vuesax.css"; //Vuesax styles
import "material-icons/iconfont/material-icons.css";
import "vue-datetime/dist/vue-datetime.css";
import "./assets/scss/style.scss";

import api from "./api/index";
import handleError from "./api/handle-error";
import router from "./router/router";
import helper from "./utils/helper";
import caseConvert from "./utils/case-convert";
import store from "./store/store";
import lang from './lang/'

import App from "./apps/App.vue";

Vue.config.productionTip = false;
Vue.config.devtools = true;

Vue.use(Vuesax);
Vue.use(VueI18n);
Vue.use(Datetime);
Vue.component("datetime", Datetime);

const i18n = new VueI18n({
  locale: 'id',
  fallbackLocale: 'en',
  messages: lang
})

Vue.prototype.$api = api;
Vue.prototype.$handleError = handleError;
Vue.prototype.$helper = helper;
Vue.prototype.$caseConvert = caseConvert;
Vue.prototype.$constants = {
  MOBILE: 'mobile',
  DESKTOP: 'desktop'
}
Vue.prototype.$loadingConfig = {
  type: "sound",
}

let baseUrl = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
: "badaso-admin";
Vue.prototype.$baseUrl = '/' + baseUrl;

const app = new Vue({
  store,
  router,
  i18n,
  render: (h) => h(App),
}).$mount("#app");
