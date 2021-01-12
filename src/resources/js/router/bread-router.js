import Browse from "./../views/bread/browse.vue";
import Add from "./../views/bread/add.vue";
import Edit from "./../views/bread/edit.vue";
import Read from "./../views/bread/read.vue";

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
