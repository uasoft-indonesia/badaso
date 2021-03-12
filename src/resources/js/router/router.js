import Vue from "vue";
import VueRouter from "vue-router";

import PublicRoute from "./public-router";
import AdminRoute from "./admin-router";
import AuthRoute from "./auth-router";
import ConfigurationRoute from "./configuration-router";
import GeneratedRoute from "./generated-router";

import AdminContainer from "./../layout/admin-panel/AdminContainer.vue";
import AuthContainer from "./../layout/auth/AuthContainer.vue";
import LandingPageContainer from "./../layout/landing-page/LandingPageContainer.vue";

import Home from "./../views/home.vue";
import Profile from "./../views/user/profile.vue";

import PageNotFound from "./../views/error/404.vue";

Vue.use(VueRouter);

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

const menuKey = process.env.MIX_DEFAULT_MENU
        ? process.env.MIX_DEFAULT_MENU
        : "admin";

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
      children: [...AuthRoute],
    },
    {
      path: "",
      name: "LandingPage",
      component: LandingPageContainer,
      meta: {
        guest: true,
      },
      children: [...PublicRoute],
    },
    {
      path: "",
      name: "AdminPanel",
      component: AdminContainer,
      meta: {
        authenticatedUser: true,
      },
      children: [
        {
          path: prefix,
          redirect: prefix + "/home",
        },
        {
          path: prefix + "/" + menuKey,
          redirect: prefix + "/home",
        },
        {
          path: prefix + "/home",
          name: "Home",
          component: Home,
          meta: {
            title: "Home",
          },
        },
        {
          path: prefix + "/profile",
          name: "Profile",
          component: Profile,
          meta: {
            title: "Profile",
          },
        },

        ...ConfigurationRoute,
        ...AdminRoute,
        ...GeneratedRoute,
      ],
    },
    {
      path: "*",
      component: AuthContainer,
      redirect: prefix + "/404",
      children: [
        {
          path: prefix + "/404",
          name: "PageNotFound",
          component: PageNotFound,
        },
      ],
    },
  ],
});

router.beforeEach((to, from, next) => {
  document.title = to.meta.title ? to.meta.title : to.name;
  if (to.matched.some((record) => record.meta.authenticatedUser)) {
    if (localStorage.getItem("token") == null) {
      next({ name: "Login" });
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
