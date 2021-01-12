import Vue from "vue";
import Vuesax from "vuesax";
import { Datetime } from "vue-datetime";

import "vuesax/dist/vuesax.css"; //Vuesax styles
import "material-icons/iconfont/material-icons.css";
import "boxicons";
import "boxicons/css/boxicons.min.css";
import "vue-datetime/dist/vue-datetime.css";
import "./assets/scss/style.scss";

import api from "./api/index";
import handleError from "./api/handle-error";
import router from "./router/router";
import helper from "./utils/helper";
import caseConvert from "./utils/case-convert";
import store from "./store/store";

import App from "./apps/App.vue";

Vue.config.productionTip = false;
Vue.config.devtools = true;

Vue.use(Vuesax);
Vue.use(Datetime);
Vue.component("datetime", Datetime);

Vue.prototype.$api = api;
Vue.prototype.$handleError = handleError;
Vue.prototype.$helper = helper;
Vue.prototype.$caseConvert = caseConvert;

let baseUrl = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
: "badaso-admin";
Vue.prototype.$baseUrl = '/' + baseUrl;

const app = new Vue({
  store,
  router,
  render: (h) => h(App),
}).$mount("#app");
