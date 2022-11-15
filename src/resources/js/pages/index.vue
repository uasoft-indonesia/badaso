<template>
  <component
    v-if="globalComponentList[defaultComponent]"
    v-bind:is="globalComponentList[defaultComponent]"
  ></component>
  <component v-else v-bind:is="defaultComponent"></component>
</template>

<script>
import AuthForgotPassword from "./auth/forgot-password";
import AuthLogin from "./auth/login";
import SecretLogin from "./auth/secret-login";
import AuthRegister from "./auth/register";
import AuthResetPassword from "./auth/reset-password";
import AuthVerify from "./auth/verify";

import Home from "./home";
import UserProfile from "./user/profile";

import ActivityLogBrowse from "./activity-log/browse";
import ActivityLogRead from "./activity-log/read";
import CrudGeneratedBrowse from "./crud-generated/browse";
import CrudGeneratedBrowseBin from "./crud-generated/browse-bin";
import CrudGeneratedRead from "./crud-generated/read";
import CrudGeneratedEdit from "./crud-generated/edit";
import CrudGeneratedAdd from "./crud-generated/add";
import CrudGeneratedSort from "./crud-generated/sort";
import CrudManagementBrowse from "./crud-management/browse";
import CrudManagementRead from "./crud-management/read";
import CrudManagementEdit from "./crud-management/edit";
import CrudManagementAdd from "./crud-management/add";
import DatabaseManagementBrowse from "./database-management/browse";
import DatabaseManagementAdd from "./database-management/add";
import DatabaseManagementAlter from "./database-management/edit";
import MenuManagementBrowse from "./menu-management/browse";
import MenuManagementEdit from "./menu-management/edit";
import MenuManagementAdd from "./menu-management/add";
import MenuManagementBuilder from "./menu-management/builder";
import MenuManagementPermissions from "./menu-management/permissions";
import PermissionManagementBrowse from "./permission-management/browse";
import PermissionManagementRead from "./permission-management/read";
import PermissionManagementEdit from "./permission-management/edit";
import PermissionManagementAdd from "./permission-management/add";
import RoleManagementBrowse from "./role-management/browse";
import RoleManagementRead from "./role-management/read";
import RoleManagementEdit from "./role-management/edit";
import RoleManagementAdd from "./role-management/add";
import RoleManagementPermissions from "./role-management/permissions";
import ConfigurationBrowse from "./configuration/browse";
import ConfigurationAdd from "./configuration/add";
import UserManagementBrowse from "./user-management/browse";
import UserManagementRead from "./user-management/read";
import UserManagementEdit from "./user-management/edit";
import UserManagementAdd from "./user-management/add";
import UserManagementRoles from "./user-management/roles";
import FileManagerBrowse from "./file-manager/browse";
import ImageManagerBrowse from "./image-manager/browse";
import LogViewerBrowse from "./log-viewer/browse";
import NotificationBrowse from "./notification/browse";
import DataPendingAddBrowse from "./data-pending-add/browse";
import DataPendingEditRead from "./data-pending-edit/read";
import ApiDocsBrowse from "./api-documentation/browse";

export default {
  components: {
    AuthForgotPassword,
    AuthLogin,
    SecretLogin,
    AuthRegister,
    AuthResetPassword,
    AuthVerify,
    ActivityLogBrowse,
    ActivityLogRead,
    Home,
    UserProfile,
    CrudGeneratedBrowse,
    CrudGeneratedBrowseBin,
    CrudGeneratedRead,
    CrudGeneratedEdit,
    CrudGeneratedAdd,
    CrudGeneratedSort,
    CrudManagementBrowse,
    CrudManagementRead,
    CrudManagementEdit,
    CrudManagementAdd,
    DatabaseManagementBrowse,
    DatabaseManagementAdd,
    DatabaseManagementAlter,
    MenuManagementBrowse,
    MenuManagementEdit,
    MenuManagementAdd,
    MenuManagementBuilder,
    MenuManagementPermissions,
    PermissionManagementBrowse,
    PermissionManagementRead,
    PermissionManagementEdit,
    PermissionManagementAdd,
    RoleManagementBrowse,
    RoleManagementRead,
    RoleManagementEdit,
    RoleManagementAdd,
    RoleManagementPermissions,
    ConfigurationBrowse,
    ConfigurationAdd,
    UserManagementBrowse,
    UserManagementRead,
    UserManagementEdit,
    UserManagementAdd,
    UserManagementRoles,
    FileManagerBrowse,
    LogViewerBrowse,
    NotificationBrowse,
    DataPendingAddBrowse,
    DataPendingEditRead,
    ApiDocsBrowse,
    ImageManagerBrowse,
  },
  name: "ActivityLogIndex",
  data: () => ({
    globalComponentList: {},
    defaultComponent: null,
  }),
  mounted() {
    const routeName = this.$route.name;
    const componentName = this.$caseConvert.kebab(routeName);
    const slug = this.$route.params ? this.$route.params.slug : "";
    this.globalComponentList = this.constructor.superOptions.components;

    if (routeName === "CrudGeneratedBrowse") {
      const generatedComponentName = slug + "-browse";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedRead") {
      const generatedComponentName = slug + "-read";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedEdit") {
      const generatedComponentName = slug + "-edit";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedAdd") {
      const generatedComponentName = slug + "-add";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else if (routeName === "CrudGeneratedSort") {
      const generatedComponentName = slug + "-sort";
      if (this.globalComponentList[generatedComponentName]) {
        this.defaultComponent = generatedComponentName;
      } else {
        this.defaultComponent = componentName;
      }
    } else {
      this.defaultComponent = componentName;
    }
  },
  methods: {},
};
</script>
