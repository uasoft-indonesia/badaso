import PermissionBrowse from "./../views/permission-management/browse";
import PermissionRead from "./../views/permission-management/read";
import PermissionEdit from "./../views/permission-management/edit";
import PermissionAdd from "./../views/permission-management/add";

import RoleBrowse from "./../views/role-management/browse";
import RoleRead from "./../views/role-management/read";
import RoleEdit from "./../views/role-management/edit";
import RoleAdd from "./../views/role-management/add";
import RolePermissions from "./../views/role-management/permissions";

import UserBrowse from "./../views/user-management/browse";
import UserRead from "./../views/user-management/read";
import UserEdit from "./../views/user-management/edit";
import UserAdd from "./../views/user-management/add";
import UserRoles from "./../views/user-management/roles";

import MenuBrowse from "./../views/menu-management/browse";
import MenuEdit from "./../views/menu-management/edit";
import MenuAdd from "./../views/menu-management/add";
import MenuBuilder from "./../views/menu-management/builder";
import MenuPermissions from "./../views/menu-management/permissions";

import CRUDManagementBrowse from "./../views/crud-management/browse";
import CRUDManagementRead from "./../views/crud-management/read";
import CRUDManagementEdit from "./../views/crud-management/edit";
import CRUDManagementAdd from "./../views/crud-management/add";

import SiteBrowse from "./../views/site-management/browse";
import SiteAdd from "./../views/site-management/add";

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

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
];
