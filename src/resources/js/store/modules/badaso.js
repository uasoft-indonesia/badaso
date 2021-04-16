import api from "../../api";
import createPersistedState from "vuex-persistedstate";
import helpers from "../../utils/helper";
import lang from "../../lang";

export default {
  namespaced: true,
  state: {
    isSidebarActive: false,
    reduceSidebar: false,
    themeColor: "#2962ff",
    menu: {},
    configurationMenu: {},
    componentList: [],
    groupList: [],
    config: {},
    user: {},
    locale: lang.languages,
    selectedLocale: {
      key: "en",
      label: "English",
    },
    keyIssue: {
      invalid: false,
    },
    authorizationIssue: {
      unauthorized: false,
    },
    verified: false,
  },
  mutations: {
    //This is for Sidbar trigger in mobile
    IS_SIDEBAR_ACTIVE(state, value) {
      state.isSidebarActive = value;
    },
    REDUCE_SIDEBAR(state, value) {
      state.reduceSidebar = value;
    },
    FETCH_MENU(state) {
      const menuKey = process.env.MIX_DEFAULT_MENU
        ? process.env.MIX_DEFAULT_MENU
        : "admin";
      const prefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
        ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
        : "badaso-admin";
      api.badasoMenu
        .browseItemByKey({
          menu_key: menuKey,
        })
        .then((res) => {
          let menuItems = res.data.menuItems;
          menuItems.map((item) => {
            if (helpers.isValidHttpUrl(item.url)) {
              item.url = item.url;
            } else {
              item.url = "/" + prefix + "" + item.url;
            }

            if (item.children && item.children.length > 0) {
              item.children.map((subItem) => {
                if (helpers.isValidHttpUrl(subItem.url)) {
                  subItem.url = subItem.url;
                } else {
                  subItem.url = "/" + prefix + "" + subItem.url;
                }
                return subItem;
              });
            }
            return item;
          });
          state.menu = {
            menu: res.data.menu,
            menuItems: menuItems,
          };
        })
        .catch((err) => {});
    },
    FETCH_CONFIGURATION_MENU(state) {
      const prefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
        ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
        : "badaso-admin";
      api.badasoMenu
        .browseItemByKey({
          menu_key: "configuration",
        })
        .then((res) => {
          let menuItems = res.data.menuItems;
          menuItems.map((item) => {
            if (helpers.isValidHttpUrl(item.url)) {
              item.url = item.url;
            } else {
              item.url = "/" + prefix + "" + item.url;
            }

            if (item.children && item.children.length > 0) {
              item.children.map((subItem) => {
                if (helpers.isValidHttpUrl(subItem.url)) {
                  subItem.url = subItem.url;
                } else {
                  subItem.url = "/" + prefix + "" + subItem.url;
                }
                return subItem;
              });
            }
            return item;
          });
          state.configurationMenu = {
            menu: res.data.menu,
            menuItems: menuItems,
          };
        })
        .catch((err) => {});
    },
    FETCH_COMPONENT(state) {
      api.badasoData
        .component()
        .then((res) => {
          state.componentList = res.data.components;
        })
        .catch((err) => {});
    },
    FETCH_CONFIGURATION_GROUPS(state) {
      api.badasoData
        .configurationGroups()
        .then((res) => {
          state.groupList = res.data.groups;
        })
        .catch((err) => {});
    },
    FETCH_CONFIGURATION(state) {
      api.badasoConfiguration
        .applyable()
        .then((res) => {
          state.config = res.data.configuration;
        })
        .catch((err) => {});
    },
    FETCH_USER(state) {
      api.badasoAuthUser
        .user()
        .then((res) => {
          state.user = res.data.user;
        })
        .catch((err) => {});
    },
    SET_LOCALE(state, value) {
      state.selectedLocale = value;
    },
    SET_KEY_ISSUE(state, value) {
      state.keyIssue = value;
    },
    SET_AUTH_ISSUE(state, value) {
      state.authorizationIssue = value;
    },
    LOGOUT(state) {
      localStorage.clear();
      window.location.reload();
    },
    VERIFY_BADASO(state) {
      api.badaso
        .verify()
        .then((res) => {
          state.verified = true;
        })
        .catch((err) => {
          state.keyIssue = {
            ...err, invalid: true,
          };
          state.verified = true;
        });
    },
  },
  actions: {},
  getters: {
    getMenu: (state) => {
      return state.menu;
    },
    getConfigurationMenu: (state) => {
      return state.configurationMenu;
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
    getLocale: (state) => {
      return state.locale;
    },
    getSelectedLocale: (state) => {
      return state.selectedLocale;
    },
    isVerified: (state) => {
      return state.verified;
    },
  },
  plugins: [createPersistedState()],
};
