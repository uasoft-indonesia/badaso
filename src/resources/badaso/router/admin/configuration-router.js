import Pages from "./../../pages/index.vue";

const prefix = import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-dashboard";

const logViewer = import.meta.env.VITE_LOG_VIEWER_ROUTE
  ? import.meta.env.VITE_LOG_VIEWER_ROUTE
  : "log-viewer";

const apiDocs = import.meta.env.VITE_API_DOCS_ROUTE
  ? import.meta.env.VITE_API_DOCS_ROUTE
  : "api-docs";
export default [
  {
    path: prefix + "/permission",
    name: "PermissionManagementBrowse",
    component: Pages,
    meta: {
      title: "Browse Permission",
    },
  },
  {
    path: prefix + "/permission/:id/detail",
    name: "PermissionManagementRead",
    component: Pages,
    meta: {
      title: "Detail Permission",
    },
  },
  {
    path: prefix + "/permission/:id/edit",
    name: "PermissionManagementEdit",
    component: Pages,
    meta: {
      title: "Edit Permission",
    },
  },
  {
    path: prefix + "/permission/add",
    name: "PermissionManagementAdd",
    component: Pages,
    meta: {
      title: "Add Permission",
    },
  },

  {
    path: prefix + "/role",
    name: "RoleManagementBrowse",
    component: Pages,
    meta: {
      title: "Browse Role",
    },
  },
  {
    path: prefix + "/role/:id/detail",
    name: "RoleManagementRead",
    component: Pages,
    meta: {
      title: "Detail Role",
    },
  },
  {
    path: prefix + "/role/:id/edit",
    name: "RoleManagementEdit",
    component: Pages,
    meta: {
      title: "Edit Role",
    },
  },
  {
    path: prefix + "/role/:id/permissions",
    name: "RoleManagementPermissions",
    component: Pages,
    meta: {
      title: "Browse Role Permission",
    },
  },
  {
    path: prefix + "/role/add",
    name: "RoleManagementAdd",
    component: Pages,
    meta: {
      title: "Add Role",
    },
  },

  {
    path: prefix + "/user",
    name: "UserManagementBrowse",
    component: Pages,
    meta: {
      title: "Browse User",
    },
  },
  {
    path: prefix + "/user/:id/detail",
    name: "UserManagementRead",
    component: Pages,
    meta: {
      title: "Detail User",
    },
  },
  {
    path: prefix + "/user/:id/edit",
    name: "UserManagementEdit",
    component: Pages,
    meta: {
      title: "Edit User",
    },
  },
  {
    path: prefix + "/user/:id/roles",
    name: "UserManagementRoles",
    component: Pages,
    meta: {
      title: "Browse User Roles",
    },
  },
  {
    path: prefix + "/user/add",
    name: "UserManagementAdd",
    component: Pages,
    meta: {
      title: "Add User",
    },
  },

  {
    path: prefix + "/menu",
    name: "MenuManagementBrowse",
    component: Pages,
    meta: {
      title: "Browse Menu",
    },
  },
  {
    path: prefix + "/menu/:id/edit",
    name: "MenuManagementEdit",
    component: Pages,
    meta: {
      title: "Edit Menu",
    },
  },
  {
    path: prefix + "/menu/:id/builder",
    name: "MenuManagementBuilder",
    component: Pages,
    meta: {
      title: "Build Menu",
    },
  },
  {
    path: prefix + "/menu/:id/:itemId/permissions",
    name: "MenuManagementPermissions",
    component: Pages,
    meta: {
      title: "Add Menu Item Permissions",
    },
  },
  {
    path: prefix + "/menu/add",
    name: "MenuManagementAdd",
    component: Pages,
    meta: {
      title: "Add Menu",
    },
  },
  {
    path: prefix + "/crud",
    name: "CrudManagementBrowse",
    component: Pages,
    meta: {
      title: "Browse CRUD",
    },
  },
  {
    path: prefix + "/crud/read",
    name: "CrudManagementRead",
    component: Pages,
    meta: {
      title: "Detail CRUD",
    },
  },
  {
    path: prefix + "/crud/edit/:tableName",
    name: "CrudManagementEdit",
    component: Pages,
    meta: {
      title: "Edit CRUD",
    },
  },
  {
    path: prefix + "/crud/add/:tableName",
    name: "CrudManagementAdd",
    component: Pages,
    meta: {
      title: "Add CRUD",
    },
  },

  {
    path: prefix + "/database",
    name: "DatabaseManagementBrowse",
    component: Pages,
    meta: {
      title: "Browse Database",
    },
  },
  {
    path: prefix + "/database/add",
    name: "DatabaseManagementAdd",
    component: Pages,
    meta: {
      title: "Add Database",
    },
  },
  {
    path: prefix + "/database/alter/:tableName",
    name: "DatabaseManagementAlter",
    component: Pages,
    meta: {
      title: "Alter Database",
    },
  },

  {
    path: prefix + "/configuration",
    name: "ConfigurationBrowse",
    component: Pages,
    meta: {
      title: "Configuration",
    },
  },
  {
    path: prefix + "/configuration/add",
    name: "ConfigurationAdd",
    component: Pages,
    meta: {
      title: "Add Configuration",
    },
  },
  {
    path: prefix + "/activity-log",
    name: "ActivityLogBrowse",
    component: Pages,
    meta: {
      title: "Browse Activity Log",
    },
  },
  {
    path: prefix + "/activity-log/:id/detail",
    name: "ActivityLogRead",
    component: Pages,
    meta: {
      title: "Detail Activity Log",
    },
  },
  {
    path: prefix + "/" + logViewer,
    name: "LogViewerBrowse",
    // beforeEnter() {
    //   location.href = "/" + logViewer;
    // },
    component: Pages,
    meta: {
      title: "Browse Log Viewer",
    },
  },
  {
    path: prefix + "/file-manager",
    name: "FileManagerBrowse",
    component: Pages,
    meta: {
      title: "Files",
    },
  },
  {
    path: prefix + "/image-manager",
    name: "ImageManagerBrowse",
    component: Pages,
    meta: {
      title: "Images",
    },
  },
  {
    path: prefix + "/notification",
    name: "NotificationBrowse",
    component: Pages,
    meta: {
      title: "Notification",
    },
  },
  {
    path: prefix + "/data-pending-add/:urlBase64",
    name: "DataPendingAddBrowse",
    component: Pages,
    meta: {
      title: "Data Pending Add",
    },
  },
  {
    path: prefix + "/data-pending-edit/:urlBase64",
    name: "DataPendingEditRead",
    component: Pages,
    meta: {
      title: "Data Pending Edit",
    },
  },
  {
    path: prefix + "/" + apiDocs,
    name: "ApiDocsBrowse",
    component: Pages,
    meta: {
      title: "Browse API Documentation",
    },
  },
];
