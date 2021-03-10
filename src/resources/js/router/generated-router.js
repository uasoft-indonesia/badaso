import Browse from "./../views/crud-generated/browse.vue";
import Add from "./../views/crud-generated/add.vue";
import Edit from "./../views/crud-generated/edit.vue";
import Read from "./../views/crud-generated/read.vue";
import Sort from "./../views/crud-generated/sort.vue";

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

const menuKey = process.env.MIX_DEFAULT_MENU
        ? process.env.MIX_DEFAULT_MENU
        : "admin";

export default [
  {
    path: prefix + "/"+ menuKey + "/:slug",
    name: "EntityBrowse",
    component: Browse,
    meta: {
      title: "Browse Entity",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/add",
    name: "EntityAdd",
    component: Add,
    meta: {
      title: "Add Entity",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/sort",
    name: "EntitySort",
    component: Sort,
    meta: {
      title: "Sort Data Entity",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/:id",
    name: "EntityRead",
    component: Read,
    meta: {
      title: "Detail Entity",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/:id/edit",
    name: "EntityEdit",
    component: Edit,
    meta: {
      title: "Edit Entity",
    },
  },
];
