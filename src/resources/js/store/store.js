import Vue from "vue";
import Vuex from "vuex";
import api from "../api"

Vue.use(Vuex);
/* eslint-disable */
export default new Vuex.Store({
  state: {
    isSidebarActive: false,
    themeColor: "#2962ff",
    menuList: [],
    componentList: [],
    groupList: [
      {
        label: "Dashboard Page",
        value: "dashboard",
      },
      {
        label: "Landing Page",
        value: "landing",
      },
    ],
    config: {},
  },
  mutations: {
    //This is for Sidbar trigger in mobile
    IS_SIDEBAR_ACTIVE(state, value) {
      state.isSidebarActive = value;
    },
    FETCH_MENU(state) {
      const menuKey = process.env.MIX_DEFAULT_MENU ? process.env.MIX_DEFAULT_MENU : 'dashboard';
      const prefix = process.env.MIX_DASHBOARD_ROUTE_PREFIX ? process.env.MIX_DASHBOARD_ROUTE_PREFIX : 'badaso-admin';
      api.menu.browseItemByKey({
        menu_key: menuKey
      })
        .then((res) => {
          let menuItems = res.data_list
          for (var i = 0, len = menuItems.length; i < len; i++) {
            menuItems[i].link = '/'+prefix+'/main/'+menuItems[i].url
          }
          state.menuList = menuItems
        })
        .catch((err) => {
        });
    },
    FETCH_COMPONENT(state) {
      api.data.component()
        .then((res) => {
          state.componentList = res.data_list
        })
        .catch((err) => {
        });
    },
    FETCH_CONFIGURATION(state) {
      api.configuration.applyable()
        .then((res) => {
          state.config = res.data_detail
        })
        .catch((err) => {})
    }
  },
  actions: {},
  getters: {
    getMenu: state => {
      return state.menuList
    },
    getComponent: state => {
      return state.componentList
    },
    getSiteGroup: state => {
      return state.groupList
    },
    getConfig: state => {
      return state.config
    }
  },
});
