import Vue from "vue";
import Vuex from "vuex";
import api from "../api";
import createPersistedState from "vuex-persistedstate";

Vue.use(Vuex);
/* eslint-disable */
export default new Vuex.Store({
  state: {
    isSidebarActive: false,
    reduceSidebar: false,
    themeColor: "#2962ff",
    menuList: [],
    configurationMenuList: [],
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
    user: {},
  },
  mutations: {
    //This is for Sidbar trigger in mobile
    IS_SIDEBAR_ACTIVE(state, value) {
      state.isSidebarActive = value;
    },
    REDUCE_SIDEBAR(state, value) {
      state.reduceSidebar = !state.reduceSidebar;
    },
    FETCH_MENU(state) {
      const menuKey = process.env.MIX_DEFAULT_MENU
        ? process.env.MIX_DEFAULT_MENU
        : "dashboard";
      const prefix = process.env.MIX_DASHBOARD_ROUTE_PREFIX
        ? process.env.MIX_DASHBOARD_ROUTE_PREFIX
        : "badaso-admin";
      api.menu
        .browseItemByKey({
          menu_key: menuKey,
        })
        .then((res) => {
          let menuItems = res.data.menuItems;
          for (var i = 0, len = menuItems.length; i < len; i++) {
            menuItems[i].link = menuItems[i].url;
          }
          state.menuList = menuItems;
        })
        .catch((err) => {});
    },
    FETCH_CONFIGURATION_MENU(state) {
      const prefix = process.env.MIX_DASHBOARD_ROUTE_PREFIX
        ? process.env.MIX_DASHBOARD_ROUTE_PREFIX
        : "badaso-admin";
      api.menu
        .browseItemByKey({
          menu_key: "configuration",
        })
        .then((res) => {
          let menuItems = res.data.menuItems;
          menuItems.map((item) => {
            item.url = "/" + prefix + "" + item.url;
            if (item.children && item.children.length > 0) {
              item.children.map((subItem) => {
                subItem.url = "/" + prefix + "" + subItem.url;
                return subItem;
              });
            }
            return item;
          });
          state.configurationMenuList = menuItems;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    FETCH_COMPONENT(state) {
      api.data
        .component()
        .then((res) => {
          state.componentList = res.data;
        })
        .catch((err) => {});
    },
    FETCH_CONFIGURATION(state) {
      api.configuration
        .applyable()
        .then((res) => {
          state.config = res.data;
        })
        .catch((err) => {});
    },
    FETCH_USER(state) {
      api.auth
        .user()
        .then((res) => {
          console.log(res.data)
          state.user = res.data;
        })
        .catch((err) => {});
    },
  },
  actions: {},
  getters: {
    getMenu: (state) => {
      return state.menuList;
    },
    getConfigurationMenu: (state) => {
      return state.configurationMenuList;
    },
    getComponent: (state) => {
      return state.componentList;
    },
    getSiteGroup: (state) => {
      return state.groupList;
    },
    getConfig: (state) => {
      return state.config;
    },
    getUser: (state) => {
      return state.user;
    },
  },
  plugins: [createPersistedState()],
});
