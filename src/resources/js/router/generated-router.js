import Browse from "./../views/crud-generated/browse.vue";
import Add from "./../views/crud-generated/add.vue";
import Edit from "./../views/crud-generated/edit.vue";
import Read from "./../views/crud-generated/read.vue";
import Sort from "./../views/crud-generated/sort.vue";

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

export default [
  {
    path: prefix + "/main/:slug",
    name: "EntityBrowse",
    component: Browse,
    meta: {
      title: "Browse Entity",
    },
  },
  {
    path: prefix + "/main/:slug/add",
    name: "EntityAdd",
    component: Add,
    meta: {
      title: "Add Entity",
    },
  },
  {
    path: prefix + "/main/:slug/sort",
    name: "EntitySort",
    component: Sort,
    meta: {
      title: "Sort Data Entity",
    },
  },
  {
    path: prefix + "/main/:slug/:id",
    name: "EntityRead",
    component: Read,
    meta: {
      title: "Detail Entity",
    },
  },
  {
    path: prefix + "/main/:slug/:id/edit",
    name: "EntityEdit",
    component: Edit,
    meta: {
      title: "Edit Entity",
    },
  },
];
