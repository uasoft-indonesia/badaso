import PermissionBrowse from "./../pages/permission-management/browse";
import PermissionRead from "./../pages/permission-management/read";
import PermissionEdit from "./../pages/permission-management/edit";
import PermissionAdd from "./../pages/permission-management/add";

import RoleBrowse from "./../pages/role-management/browse";
import RoleRead from "./../pages/role-management/read";
import RoleEdit from "./../pages/role-management/edit";
import RoleAdd from "./../pages/role-management/add";
import RolePermissions from "./../pages/role-management/permissions";

import UserBrowse from "./../pages/user-management/browse";
import UserRead from "./../pages/user-management/read";
import UserEdit from "./../pages/user-management/edit";
import UserAdd from "./../pages/user-management/add";
import UserRoles from "./../pages/user-management/roles";

import MenuBrowse from "./../pages/menu-management/browse";
import MenuEdit from "./../pages/menu-management/edit";
import MenuAdd from "./../pages/menu-management/add";
import MenuBuilder from "./../pages/menu-management/builder";
import MenuPermissions from "./../pages/menu-management/permissions";

import CRUDManagementBrowse from "./../pages/crud-management/browse";
import CRUDManagementRead from "./../pages/crud-management/read";
import CRUDManagementEdit from "./../pages/crud-management/edit";
import CRUDManagementAdd from "./../pages/crud-management/add";

import SiteBrowse from "./../pages/site-management/browse";
import SiteAdd from "./../pages/site-management/add";

import ActivityLogBrowse from './../pages/activity-log/browse'
import ActivityLogRead from './../pages/activity-log/read'

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let log_viewer = process.env.MIX_LOG_VIEWER_ROUTE
  ? process.env.MIX_LOG_VIEWER_ROUTE
  : "log-viewer";

let prefix = "/" + prefix_env;

export default [
  {
    path: prefix + "/permission",
    name: "PermissionBrowse",
    component: PermissionBrowse,
    meta: {
      title: "Browse Permission",
    },
  },
  {
    path: prefix + "/permission/:id/detail",
    name: "PermissionRead",
    component: PermissionRead,
    meta: {
      title: "Detail Permission",
    },
  },
  {
    path: prefix + "/permission/:id/edit",
    name: "PermissionEdit",
    component: PermissionEdit,
    meta: {
      title: "Edit Permission",
    },
  },
  {
    path: prefix + "/permission/add",
    name: "PermissionAdd",
    component: PermissionAdd,
    meta: {
      title: "Add Permission",
    },
  },

  {
    path: prefix + "/role",
    name: "RoleBrowse",
    component: RoleBrowse,
    meta: {
      title: "Browse Role",
    },
  },
  {
    path: prefix + "/role/:id/detail",
    name: "RoleRead",
    component: RoleRead,
    meta: {
      title: "Detail Role",
    },
  },
  {
    path: prefix + "/role/:id/edit",
    name: "RoleEdit",
    component: RoleEdit,
    meta: {
      title: "Edit Role",
    },
  },
  {
    path: prefix + "/role/:id/permissions",
    name: "RolePermissions",
    component: RolePermissions,
    meta: {
      title: "Browse Role Permission",
    },
  },
  {
    path: prefix + "/role/add",
    name: "RoleAdd",
    component: RoleAdd,
    meta: {
      title: "Add Role",
    },
  },

  {
    path: prefix + "/user",
    name: "UserBrowse",
    component: UserBrowse,
    meta: {
      title: "Browse User",
    },
  },
  {
    path: prefix + "/user/:id/detail",
    name: "UserRead",
    component: UserRead,
    meta: {
      title: "Detail User",
    },
  },
  {
    path: prefix + "/user/:id/edit",
    name: "UserEdit",
    component: UserEdit,
    meta: {
      title: "Edit User",
    },
  },
  {
    path: prefix + "/user/:id/roles",
    name: "UserRoles",
    component: UserRoles,
    meta: {
      title: "Browse User Roles",
    },
  },
  {
    path: prefix + "/user/add",
    name: "UserAdd",
    component: UserAdd,
    meta: {
      title: "Add User",
    },
  },

  {
    path: prefix + "/menu",
    name: "MenuBrowse",
    component: MenuBrowse,
    meta: {
      title: "Browse Menu",
    },
  },
  {
    path: prefix + "/menu/:id/edit",
    name: "MenuEdit",
    component: MenuEdit,
    meta: {
      title: "Edit Menu",
    },
  },
  {
    path: prefix + "/menu/:id/builder",
    name: "MenuBuilder",
    component: MenuBuilder,
    meta: {
      title: "Build Menu",
    },
  },
  {
    path: prefix + "/menu/:id/:itemId/permissions",
    name: "MenuPermissions",
    component: MenuPermissions,
    meta: {
      title: "Add Menu Item Permissions",
    },
  },
  {
    path: prefix + "/menu/add",
    name: "MenuAdd",
    component: MenuAdd,
    meta: {
      title: "Add Menu",
    },
  },
  {
    path: prefix + "/crud",
    name: "CRUDManagementBrowse",
    component: CRUDManagementBrowse,
    meta: {
      title: "Browse CRUD",
    },
  },
  {
    path: prefix + "/crud/read",
    name: "CRUDManagementRead",
    component: CRUDManagementRead,
    meta: {
      title: "Detail CRUD",
    },
  },
  {
    path: prefix + "/crud/edit/:tableName",
    name: "CRUDManagementEdit",
    component: CRUDManagementEdit,
    meta: {
      title: "Edit CRUD",
    },
  },
  {
    path: prefix + "/crud/add/:tableName",
    name: "CRUDManagementAdd",
    component: CRUDManagementAdd,
    meta: {
      title: "Add CRUD",
    },
  },

  {
    path: prefix + "/site",
    name: "SiteBrowse",
    component: SiteBrowse,
    meta: {
      title: "Site Configuration",
    },
  },
  {
    path: prefix + "/site/add",
    name: "SiteAdd",
    component: SiteAdd,
    meta: {
      title: "Add Site Configuration",
    },
  },
  {
    path: prefix + "/activity-log",
    name: "ActivityLogBrowse",
    component: ActivityLogBrowse,
    meta: {
      title: "Browse Activity Log",
    },
  },
  {
    path: prefix + "/activity-log/:id/detail",
    name: "ActivityLogRead",
    component: ActivityLogRead,
    meta: {
      title: "Detail Activity Log",
    },
  },
  {
    path: prefix + "/" + log_viewer,
    name: "LogViewer",
    beforeEnter() { location.href = "/" + log_viewer},
    meta: {
      title: "Browse Log Viewer",
    },
  },
];
