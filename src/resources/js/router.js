import Vue from "vue";
import VueRouter from "vue-router";

import MainContainer from "./layout/full/MainContainer.vue";
import AuthContainer from "./layout/full/AuthContainer.vue";

import Home from "./views/home.vue";

import Login from "./views/auth/login.vue";
import Register from "./views/auth/register.vue";
import ForgotPassword from "./views/auth/forgot-password.vue";
import ResetPassword from "./views/auth/reset-password.vue";

import PageNotFound from "./views/error/404.vue";

import Browse from "./views/bread/browse.vue";
import Add from "./views/bread/add.vue";
import Edit from "./views/bread/edit.vue";
import Read from "./views/bread/read.vue";

import PermissionBrowse from "./views/permission-management/browse";
import PermissionRead from "./views/permission-management/read";
import PermissionEdit from "./views/permission-management/edit";
import PermissionAdd from "./views/permission-management/add";

import RoleBrowse from "./views/role-management/browse";
import RoleRead from "./views/role-management/read";
import RoleEdit from "./views/role-management/edit";
import RoleAdd from "./views/role-management/add";
import RolePermissions from "./views/role-management/permissions";

import UserBrowse from "./views/user-management/browse";
import UserRead from "./views/user-management/read";
import UserEdit from "./views/user-management/edit";
import UserAdd from "./views/user-management/add";
import UserRoles from "./views/user-management/roles";

import MenuBrowse from "./views/menu-management/browse";
import MenuRead from "./views/menu-management/read";
import MenuEdit from "./views/menu-management/edit";
import MenuAdd from "./views/menu-management/add";
import MenuBuilder from "./views/menu-management/builder";

import BreadBrowse from "./views/bread-management/browse";
import BreadRead from "./views/bread-management/read";
import BreadEdit from "./views/bread-management/edit";
import BreadAdd from "./views/bread-management/add";

import SiteBrowse from "./views/site-management/browse";
import SiteRead from "./views/site-management/read";
import SiteEdit from "./views/site-management/edit";
import SiteAdd from "./views/site-management/add";

Vue.use(VueRouter);

let prefix_env = process.env.MIX_DASHBOARD_ROUTE_PREFIX
  ? process.env.MIX_DASHBOARD_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

const router = new VueRouter({
  mode: "history",
  routes: [
    {
      // ======================
      // Full Layout
      // ======================
      path: "",
      name: "Auth",
      component: AuthContainer,
      meta: {
        guest: true,
      },
      // ======================
      // Theme routes / pages
      // ======================
      children: [
        {
          path: prefix + "/login",
          name: "Login",
          component: Login,
        },
        {
          path: prefix + "/register",
          name: "Register",
          component: Register,
        },
        {
          path: prefix + "/forgot-password",
          name: "ForgotPassword",
          component: ForgotPassword,
        },
        {
          path: prefix + "/reset-password",
          name: "ResetPassword",
          component: ResetPassword,
        },
      ],
    },
    {
      // ======================
      // Full Layout
      // ======================
      path: "",
      name: "Admin",
      component: MainContainer,
      meta: {
        authenticatedUser: true,
      },
      // ======================
      // Theme routes / pages
      // ======================

      children: [
        {
          path: prefix,
          redirect: prefix + "/home",
        },
        {
          path: prefix + '/main',
          redirect: prefix + "/home",
        },
        { path: prefix + "/home", name: "Home", component: Home },

        {
          path: prefix + "/permission",
          name: "PermissionBrowse",
          component: PermissionBrowse,
        },
        {
          path: prefix + "/permission/:id/detail",
          name: "PermissionRead",
          component: PermissionRead,
        },
        {
          path: prefix + "/permission/:id/edit",
          name: "PermissionEdit",
          component: PermissionEdit,
        },
        {
          path: prefix + "/permission/add",
          name: "PermissionAdd",
          component: PermissionAdd,
        },

        { path: prefix + "/role", name: "RoleBrowse", component: RoleBrowse },
        { path: prefix + "/role/:id/detail", name: "RoleRead", component: RoleRead },
        { path: prefix + "/role/:id/edit", name: "RoleEdit", component: RoleEdit },
        { path: prefix + "/role/:id/permissions", name: "RolePermissions", component: RolePermissions },
        { path: prefix + "/role/add", name: "RoleAdd", component: RoleAdd },

        { path: prefix + "/user", name: "UserBrowse", component: UserBrowse },
        { path: prefix + "/user/:id/detail", name: "UserRead", component: UserRead },
        { path: prefix + "/user/:id/edit", name: "UserEdit", component: UserEdit },
        { path: prefix + "/user/:id/roles", name: "UserRoles", component: UserRoles },
        { path: prefix + "/user/add", name: "UserAdd", component: UserAdd },

        { path: prefix + "/menu", name: "MenuBrowse", component: MenuBrowse },
        { path: prefix + "/menu/:id/read", name: "MenuRead", component: MenuRead },
        { path: prefix + "/menu/:id/edit", name: "MenuEdit", component: MenuEdit },
        { path: prefix + "/menu/:id/builder", name: "MenuBuilder", component: MenuBuilder },
        { path: prefix + "/menu/add", name: "MenuAdd", component: MenuAdd },

        {
          path: prefix + "/bread",
          name: "BreadBrowse",
          component: BreadBrowse,
        },
        {
          path: prefix + "/bread/read",
          name: "BreadRead",
          component: BreadRead,
        },
        {
          path: prefix + "/bread/edit/:tableName",
          name: "BreadEdit",
          component: BreadEdit,
        },
        { path: prefix + "/bread/add/:tableName", name: "BreadAdd", component: BreadAdd },

        { path: prefix + "/site", name: "SiteBrowse", component: SiteBrowse },
        { path: prefix + "/site/read", name: "SiteRead", component: SiteRead },
        { path: prefix + "/site/edit", name: "SiteEdit", component: SiteEdit },
        { path: prefix + "/site/add", name: "SiteAdd", component: SiteAdd },

        {
          path: prefix + "/main/:slug",
          name: "EntityBrowse",
          component: Browse,
        },
        {
          path: prefix + "/main/:slug/:id/detail",
          name: "EntityRead",
          component: Read,
        },
        {
          path: prefix + "/main/:slug/:id/edit",
          name: "EntityEdit",
          component: Edit,
        },
        {
          path: prefix + "/main/:slug/add",
          name: "EntityAdd",
          component: Add,
        },
      ],
    },
    // {
    //   path: "*",
    //   component: AuthContainer,
    //   redirect: prefix + "/404",
    //   children: [
    //     {
    //       path: prefix + "/404",
    //       name: "PageNotFound",
    //       component: PageNotFound,
    //     }
    //   ]
    // }
  ],
});

router.beforeEach((to, from, next) => {
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
