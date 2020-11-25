import Vue from "vue";
import VueRouter from "vue-router";

import MainContainer from "./layout/full/MainContainer.vue";
import AuthContainer from "./layout/full/AuthContainer.vue";

import Home from "./views/home.vue";

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

import UserBrowse from "./views/user-management/browse";
import UserRead from "./views/user-management/read";
import UserEdit from "./views/user-management/edit";
import UserAdd from "./views/user-management/add";

import MenuBrowse from "./views/menu-management/browse";
import MenuRead from "./views/menu-management/read";
import MenuEdit from "./views/menu-management/edit";
import MenuAdd from "./views/menu-management/add";
import MenuBuilder from "./views/menu-management/builder";

import BreadBrowse from "./views/bread-management/browse";
import BreadRead from "./views/bread-management/read";
import BreadEdit from "./views/bread-management/edit";
import BreadAdd from "./views/bread-management/add";

import SiteBrowse from "./views/bread-management/browse";
import SiteRead from "./views/bread-management/read";
import SiteEdit from "./views/bread-management/edit";
import SiteAdd from "./views/bread-management/add";

Vue.use(VueRouter);

let prefix_env = process.env.MIX_DASHBOARD_ROUTE_PREFIX
  ? process.env.MIX_DASHBOARD_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

const router = new VueRouter({
  mode: "history",
  routes: [
		{ path: prefix + "/auth", name: "Login", component: AuthContainer },,
    {
      // ======================
      // Full Layout
      // ======================
      path: prefix,
      component: MainContainer,
      // ======================
      // Theme routes / pages
      // ======================

      children: [
        {
          path: prefix,
          redirect: prefix + "/home",
        },
        { path: prefix + "/home", name: "Home", component: Home },

        {
          path: prefix + "/permission",
          name: "PermissionBrowse",
          component: PermissionBrowse,
        },
        {
          path: prefix + "/permission/read",
          name: "PermissionRead",
          component: PermissionRead,
        },
        {
          path: prefix + "/permission/edit",
          name: "PermissionEdit",
          component: PermissionEdit,
        },
        {
          path: prefix + "/permission/add",
          name: "PermissionAdd",
          component: PermissionAdd,
        },

        { path: prefix + "/role", name: "RoleBrowse", component: RoleBrowse },
        { path: prefix + "/role/read", name: "RoleRead", component: RoleRead },
        { path: prefix + "/role/edit", name: "RoleEdit", component: RoleEdit },
        { path: prefix + "/role/add", name: "RoleAdd", component: RoleAdd },

        { path: prefix + "/user", name: "UserBrowse", component: UserBrowse },
        { path: prefix + "/user/read", name: "UserRead", component: UserRead },
        { path: prefix + "/user/edit", name: "UserEdit", component: UserEdit },
        { path: prefix + "/user/add", name: "UserAdd", component: UserAdd },

        { path: prefix + "/menu", name: "MenuBrowse", component: MenuBrowse },
        { path: prefix + "/menu/read", name: "MenuRead", component: MenuRead },
        { path: prefix + "/menu/edit", name: "MenuEdit", component: MenuEdit },
        { path: prefix + "/menu/add", name: "MenuAdd", component: MenuAdd },
        {
          path: prefix + "/menu/builder",
          name: "MenuBuilder",
          component: MenuBuilder,
        },

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
          path: prefix + "/bread/edit",
          name: "BreadEdit",
          component: BreadEdit,
        },
        { path: prefix + "/bread/add", name: "BreadAdd", component: BreadAdd },

        { path: prefix + "/site", name: "SiteBrowse", component: SiteBrowse },
        { path: prefix + "/site/read", name: "SiteRead", component: SiteRead },
        { path: prefix + "/site/edit", name: "SiteEdit", component: SiteEdit },
        { path: prefix + "/site/add", name: "SiteAdd", component: SiteAdd },

        {
          path: prefix + "/main/:dataType",
          name: "MainBrowse",
          component: Browse,
        },
        {
          path: prefix + "/main/:dataType/read",
          name: "MainRead",
          component: Read,
        },
        {
          path: prefix + "/main/:dataType/edit",
          name: "MainEdit",
          component: Edit,
        },
        {
          path: prefix + "/main/:dataType/add",
          name: "MainAdd",
          component: Add,
        },
      ],
    },
  ],
});

export default router;
