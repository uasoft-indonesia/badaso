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
    menu: [],
    configurationMenu: {},
    componentList: [],
    groupList: [],
    config: {},
    user: {
      avatar: "photos/shares/default-user.png",
    },
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
    isOnline: false,
    countUnreadMessage: 0,
    availableMimetypes: {
      file: {},
      image: {},
    },
  },
  mutations: {
    // This is for Sidbar trigger in mobile
    IS_SIDEBAR_ACTIVE(state, value) {
      state.isSidebarActive = value;
    },
    REDUCE_SIDEBAR(state, value) {
      state.reduceSidebar = value;
    },
    FETCH_MENU(state) {
      const prefix = import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
        ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
        : "badaso-dashboard";

      // function add prefix in children
      const menuItemAddPrefix = (arrayMenuChild, callbackMenuItemAddPrefix) => {
        arrayMenuChild = arrayMenuChild.map((menuItem) => {
          //   menuItem.url = "/" + prefix + menuItem.url;
          menuItem.url =
            menuItem.url.substring(0, 4) == "http"
              ? menuItem.url
              : "/" + prefix + menuItem.url;
          menuItem.icon = menuItem.iconClass;

          if (menuItem.children) {
            menuItem.children = callbackMenuItemAddPrefix(
              menuItem.children,
              callbackMenuItemAddPrefix
            );
          }

          return menuItem;
        });

        return arrayMenuChild;
      };

      api.badasoMenu.browseItemByKeys({}).then((res) => {
        const { data } = res;

        state.menu = data.map((menu) => {
          menu.menuItems = menuItemAddPrefix(menu.menuItems, menuItemAddPrefix);

          return menu;
        });
      });
    },
    FETCH_CONFIGURATION_MENU(state) {
      const prefix = import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
        ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
        : "badaso-dashboard";
      api.badasoMenu
        .browseItemByKey({
          menu_key: "core",
        })
        .then((res) => {
          const menuItems = res.data.menuItems;
          menuItems.map((item) => {
            if (!helpers.isValidHttpUrl(item.url)) {
              item.url = "/" + prefix + "" + item.url;
            }

            if (item.children && item.children.length > 0) {
              item.children.map((subItem) => {
                if (!helpers.isValidHttpUrl(subItem.url)) {
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
        });
    },
    FETCH_COMPONENT(state) {
      api.badasoData.component().then((res) => {
        state.componentList = res.data.components;
      });
    },
    FETCH_CONFIGURATION_GROUPS(state) {
      api.badasoData.configurationGroups().then((res) => {
        state.groupList = res.data.groups;
      });
    },
    FETCH_CONFIGURATION(state) {
      api.badasoConfiguration.applyable().then((res) => {
        state.config = res.data.configuration;
      });
    },
    FETCH_USER(state) {
      api.badasoAuthUser.user().then((res) => {
        state.user = res.data.user;
      });
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
    FETCH_FILE_CONFIGURATION(state) {
      api.badasoFile
        .browseConfiguration()
        .then((res) => {
          state.availableMimetypes.image = res.data.image;
          state.availableMimetypes.file = res.data.file;
        })
        .catch((err) => {
          console.error(err);
        });
    },
    SET_GLOBAL_STATE(state, { key, value }) {
      state[key] = value;
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
    getGlobalState: (state) => {
      return state;
    },
  },
  plugins: [createPersistedState()],
};
