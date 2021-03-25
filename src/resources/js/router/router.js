import Vue from "vue";
import VueRouter from "vue-router";

import AdminContainer from "./../layout/admin/Container";
import AuthContainer from "./../layout/auth/Container";
import LandingPageContainer from "./../layout/public/Container";

import PageNotFound from "./../pages/error/PageNotFound.vue";

const prefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? '/' + process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-admin";

const adminRouters = require.context("./admin", false, /\.js$/); // 
const authRouters = require.context("./auth", false, /\.js$/); // 
const publicRouters = require.context("./public", false, /\.js$/); // 
const otherRouters = require.context("./others", false, /\.js$/); // 

let _authRouters = []
authRouters.keys().forEach((fileName) => {
  _authRouters = [..._authRouters, ...authRouters(fileName).default]
})

let _publicRouters = []
publicRouters.keys().forEach((fileName) => {
  _publicRouters = [..._publicRouters, ...publicRouters(fileName).default]
})

let _adminRouters = []
adminRouters.keys().forEach((fileName) => {
  _adminRouters = [..._adminRouters, ...adminRouters(fileName).default]
})

let _otherRouters = []
otherRouters.keys().forEach((fileName) => {
  _otherRouters = [..._otherRouters, ...otherRouters(fileName).default]
})

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
      children: _authRouters
    },
    {
      path: "",
      name: "Public",
      component: LandingPageContainer,
      meta: {
        guest: true,
      },
      children: _publicRouters
    },
    {
      path: "",
      name: "Admin",
      component: AdminContainer,
      meta: {
        authenticatedUser: true,
      },
      children: _adminRouters,
    },
    ..._otherRouters,
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
});

export default router;
