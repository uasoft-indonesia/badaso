let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? process.env.MIX_API_ROUTE_PREFIX
: "badaso-api";

apiPrefix = '/' + apiPrefix;

export default {
  dashboard: {
    index: apiPrefix + '/v1/dashboard',
  },
  activitylog: {
    browse: apiPrefix + '/v1/activitylogs',
    read: apiPrefix + '/v1/activitylogs/read',
  },
  auth: {
    login: apiPrefix + '/v1/auth/login',
    logout: apiPrefix + '/v1/auth/logout',
    forgotPassword: apiPrefix + '/v1/auth/forgot-password',
    resetPassword: apiPrefix + '/v1/auth/reset-password',
    register: apiPrefix + '/v1/auth/register',
    verify: apiPrefix + '/v1/auth/verify',
    refreshToken: apiPrefix + '/v1/auth/refresh-token',
    user: apiPrefix + '/v1/auth/user',
    changePassword: apiPrefix + '/v1/auth/change-password'
  },
  crudManagement: {
    browse: apiPrefix + '/v1/crud',
    read: apiPrefix + '/v1/crud/read',
    readBySlug: apiPrefix + '/v1/crud/read-by-slug',
    edit: apiPrefix + '/v1/crud/edit',
    add: apiPrefix + '/v1/crud/add',
    delete: apiPrefix + '/v1/crud/delete',
  },
  table: {
    browse: apiPrefix + '/v1/table',
    read: apiPrefix + '/v1/table/read',
    relationDataBySlug: apiPrefix + '/v1/table/relation-data-by-slug'
  },
  configuration: {
    applyable: apiPrefix + '/v1/configurations/applyable',
    browse: apiPrefix + '/v1/configurations',
    read: apiPrefix + '/v1/configurations/read',
    edit: apiPrefix + '/v1/configurations/edit',
    editMultiple: apiPrefix + '/v1/configurations/edit-multiple',
    add: apiPrefix + '/v1/configurations/add',
    delete: apiPrefix + '/v1/configurations/delete',
  },
  menu: {
    browse: apiPrefix + '/v1/menus',
    read: apiPrefix + '/v1/menus/read',
    edit: apiPrefix + '/v1/menus/edit',
    add: apiPrefix + '/v1/menus/add',
    delete: apiPrefix + '/v1/menus/delete',
    browseItem: apiPrefix + '/v1/menus/item',
    arrangeItems: apiPrefix + '/v1/menus/arrange-items',
    browseItemByKey: apiPrefix + '/v1/menus/item-by-key',
    readItem: apiPrefix + '/v1/menus/item/read',
    editItem: apiPrefix + '/v1/menus/item/edit',
    editItemOrder: apiPrefix + '/v1/menus/item/edit-order',
    addItem: apiPrefix + '/v1/menus/item/add',
    deleteItem: apiPrefix + '/v1/menus/item/delete',
    itemPermissions: apiPrefix + '/v1/menus/item/permissions',
  },
  permission: {
    browse: apiPrefix + '/v1/permissions',
    read: apiPrefix + '/v1/permissions/read',
    edit: apiPrefix + '/v1/permissions/edit',
    add: apiPrefix + '/v1/permissions/add',
    delete: apiPrefix + '/v1/permissions/delete',
    deleteMultiple: apiPrefix + '/v1/permissions/delete-multiple',
  },
  role: {
    browse: apiPrefix + '/v1/roles',
    read: apiPrefix + '/v1/roles/read',
    edit: apiPrefix + '/v1/roles/edit',
    add: apiPrefix + '/v1/roles/add',
    delete: apiPrefix + '/v1/roles/delete',
    deleteMultiple: apiPrefix + '/v1/roles/delete-multiple',

    permissions: apiPrefix + '/v1/role-permissions/all-permission',
    addPermissions: apiPrefix + '/v1/role-permissions/add-edit',
  },
  user: {
    browse: apiPrefix + '/v1/users',
    read: apiPrefix + '/v1/users/read',
    edit: apiPrefix + '/v1/users/edit',
    add: apiPrefix + '/v1/users/add',
    delete: apiPrefix + '/v1/users/delete',
    deleteMultiple: apiPrefix + '/v1/users/delete-multiple',

    roles: apiPrefix + '/v1/user-roles/all-role',
    addRoles: apiPrefix + '/v1/user-roles/add-edit',
  },
  data: {
    component: apiPrefix + '/v1/data/components',
    filterOperator: apiPrefix + '/v1/data/filter-operators',
    tableRelations: apiPrefix + '/v1/data/table-relations',
    configurationGroups: apiPrefix + '/v1/data/configuration-groups'
  },
  entity: apiPrefix + '/v1/entities',
  file: {
    view: apiPrefix + '/v1/file/view',
    download: apiPrefix + '/v1/file/download'
  },
}