import Vue from "vue";
import VueRouter from "vue-router";

import AdminContainer from "./../layout/admin/Container.vue";
import AuthContainer from "./../layout/auth/Container.vue";
import LandingPageContainer from "./../layout/public/Container.vue";

import PageNotFound from "./../pages/error/PageNotFound.vue";
import Maintenance from "./../pages/maintenance.vue";

import api from "../api/index";

const prefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? "/" + process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-dashboard";

const pluginsEnv = process.env.MIX_BADASO_PLUGINS
  ? process.env.MIX_BADASO_PLUGINS
  : null;

let _authRouters = [];
let _publicRouters = [];
let _adminRouters = [];
let _otherRouters = [];

const _pluginRouters = [];
_pluginRouters.AdminContainer = [];
_pluginRouters.AuthContainer = [];
_pluginRouters.LandingPageContainer = [];

// DYNAMIC IMPORT BADASO ROUTERS
try {
  const authRouters = require.context("./auth", false, /\.js$/); //
  authRouters.keys().forEach((fileName) => {
    _authRouters = [..._authRouters, ...authRouters(fileName).default];
  });

  const publicRouters = require.context("./public", false, /\.js$/); //
  publicRouters.keys().forEach((fileName) => {
    _publicRouters = [..._publicRouters, ...publicRouters(fileName).default];
  });

  const adminRouters = require.context("./admin", false, /\.js$/); //
  adminRouters.keys().forEach((fileName) => {
    _adminRouters = [..._adminRouters, ...adminRouters(fileName).default];
  });

  const otherRouters = require.context("./others", false, /\.js$/); //
  otherRouters.keys().forEach((fileName) => {
    _otherRouters = [..._otherRouters, ...otherRouters(fileName).default];
  });

  // DYNAMIC IMPORT BADASO PLUGINS ROUTERS
  if (pluginsEnv) {
    const plugins = process.env.MIX_BADASO_PLUGINS.split(",");
    if (plugins && plugins.length > 0) {
      for (const index in plugins) {
        const plugin = plugins[index];
        const routes = require("../../../../../" +
          plugin +
          "/src/resources/js/router/routes.js").default;
        let adminRouters = [];
        let authRouters = [];
        let landingPageRouters = [];
        routes.forEach((route) => {
          switch (route.meta.useComponent) {
            case "AdminContainer":
              adminRouters = [...routes];
              break;
            case "AuthContainer":
              authRouters = [...routes];
              break;
            case "LandingPageContainer":
              landingPageRouters = [...routes];
              break;
            default:
              break;
          }
        });

        _pluginRouters.AdminContainer = [
          ..._pluginRouters.AdminContainer,
          ...adminRouters,
        ];
        _pluginRouters.AuthContainer = [
          ..._pluginRouters.AuthContainer,
          ...authRouters,
        ];
        _pluginRouters.LandingPageContainer = [
          ..._pluginRouters.LandingPageContainer,
          ...landingPageRouters,
        ];
      }
    }
  }
} catch (error) {
  console.info("Failed to load badaso plugin routers", error);
}

// DYNAMIC IMPORT CUSTOM ROUTERS
try {
  const authRouters = require.context(
    "../../../../../../../resources/js/badaso/routers/auth",
    false,
    /\.js$/
  ); //
  authRouters.keys().forEach((fileName) => {
    _authRouters = [..._authRouters, ...authRouters(fileName).default];
  });

  const publicRouters = require.context(
    "../../../../../../../resources/js/badaso/routers/public",
    false,
    /\.js$/
  ); //
  publicRouters.keys().forEach((fileName) => {
    _publicRouters = [..._publicRouters, ...publicRouters(fileName).default];
  });

  const adminRouters = require.context(
    "../../../../../../../resources/js/badaso/routers/admin",
    false,
    /\.js$/
  ); //
  adminRouters.keys().forEach((fileName) => {
    _adminRouters = [..._adminRouters, ...adminRouters(fileName).default];
  });

  const otherRouters = require.context(
    "../../../../../../../resources/js/badaso/routers/others",
    false,
    /\.js$/
  ); //
  otherRouters.keys().forEach((fileName) => {
    _otherRouters = [..._otherRouters, ...otherRouters(fileName).default];
  });
} catch (error) {
  console.info("Failed to load custom routers", error);
}

Vue.use(VueRouter);

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      path: "",
      name: "Auth",
      component: AuthContainer,
      meta: {
        guest: true,
      },
      children: [..._authRouters, ..._pluginRouters.AuthContainer],
    },
    {
      path: "",
      name: "Public",
      component: LandingPageContainer,
      meta: {
        guest: true,
      },
      children: [..._publicRouters, ..._pluginRouters.LandingPageContainer],
    },
    {
      path: "",
      name: "Admin",
      component: AdminContainer,
      meta: {
        authenticatedUser: true,
      },
      children: [..._adminRouters, ..._pluginRouters.AdminContainer],
    },
    ..._otherRouters,
    {
      path: prefix + "/maintenance",
      name: "Maintenance",
      component: Maintenance,
      meta: {
        title: "Under Maintenance",
        guest: true,
      },
    },
    {
      path: "*",
      component: AuthContainer,
      redirect: prefix + "/page-not-found",
      children: [
        {
          path: prefix + "/page-not-found",
          name: "PageNotFound",
          component: PageNotFound,
          meta: {
            title: "Page Not Found",
          },
        },
      ],
    },
  ],
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title ? to.meta.title : to.name;
  if (to.matched.some((record) => record.name !== "Maintenance")) {
    api.badasoMaintenance
      .maintenance({
        path: to.path,
      })
      .then((res) => {
        if (res.data.maintenance) {
          next({ name: "Maintenance" });
        }
      });

    if (to.matched.some((record) => record.meta.authenticatedUser)) {
      if (localStorage.getItem("token") == null) {
        next({ name: "AuthLogin" });
      } else {
        next();
      }
    } else if (to.matched.some((record) => record.meta.guest)) {
      if (localStorage.getItem("token") == null) {
        next();
      } else {
        next({ name: "Home" });
      }
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
