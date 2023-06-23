<template>
  <component
    v-if="globalComponentList[defaultComponent]"
    v-bind:is="globalComponentList[defaultComponent]"
  ></component>
  <component v-else v-bind:is="defaultComponent"></component>
</template>

<script>
import AuthForgotPassword from "./auth/forgot-password.vue";
import AuthLogin from "./auth/login.vue";
import SecretLogin from "./auth/secret-login.vue";
import AuthRegister from "./auth/register.vue";
import AuthResetPassword from "./auth/reset-password.vue";
import AuthVerify from "./auth/verify.vue";

import Home from "./home.vue";
import UserProfile from "./user/profile.vue";

import ActivityLogBrowse from "./activity-log/browse.vue";
import ActivityLogRead from "./activity-log/read.vue";
import CrudGeneratedBrowse from "./crud-generated/browse.vue";
import CrudGeneratedBrowseBin from "./crud-generated/browse-bin.vue";
import CrudGeneratedRead from "./crud-generated/read.vue";
import CrudGeneratedEdit from "./crud-generated/edit.vue";
import CrudGeneratedAdd from "./crud-generated/add.vue";
import CrudGeneratedSort from "./crud-generated/sort.vue";
import CrudManagementBrowse from "./crud-management/browse.vue";
import CrudManagementRead from "./crud-management/read.vue";
import CrudManagementEdit from "./crud-management/edit.vue";
import CrudManagementAdd from "./crud-management/add.vue";
import DatabaseManagementBrowse from "./database-management/browse.vue";
import DatabaseManagementAdd from "./database-management/add.vue";
import DatabaseManagementAlter from "./database-management/edit.vue";
import MenuManagementBrowse from "./menu-management/browse.vue";
import MenuManagementEdit from "./menu-management/edit.vue";
import MenuManagementAdd from "./menu-management/add.vue";
import MenuManagementBuilder from "./menu-management/builder.vue";
import MenuManagementPermissions from "./menu-management/permissions.vue";
import PermissionManagementBrowse from "./permission-management/browse.vue";
import PermissionManagementRead from "./permission-management/read.vue";
import PermissionManagementEdit from "./permission-management/edit.vue";
import PermissionManagementAdd from "./permission-management/add.vue";
import RoleManagementBrowse from "./role-management/browse.vue";
import RoleManagementRead from "./role-management/read.vue";
import RoleManagementEdit from "./role-management/edit.vue";
import RoleManagementAdd from "./role-management/add.vue";
import RoleManagementPermissions from "./role-management/permissions.vue";
import ConfigurationBrowse from "./configuration/browse.vue";
import ConfigurationAdd from "./configuration/add.vue";
import UserManagementBrowse from "./user-management/browse.vue";
import UserManagementRead from "./user-management/read.vue";
import UserManagementEdit from "./user-management/edit.vue";
import UserManagementAdd from "./user-management/add.vue";
import UserManagementRoles from "./user-management/roles.vue";
import FileManagerBrowse from "./file-manager/browse.vue";
import ImageManagerBrowse from "./image-manager/browse.vue";
import LogViewerBrowse from "./log-viewer/browse.vue";
import NotificationBrowse from "./notification/browse.vue";
import DataPendingAddBrowse from "./data-pending-add/browse.vue";
import DataPendingEditRead from "./data-pending-edit/read.vue";
import ApiDocsBrowse from "./api-documentation/browse.vue";

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
    ImageManagerBrowse,
    LogViewerBrowse,
    NotificationBrowse,
    DataPendingAddBrowse,
    DataPendingEditRead,
    ApiDocsBrowse,
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
