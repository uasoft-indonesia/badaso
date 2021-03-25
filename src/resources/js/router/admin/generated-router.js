import Pages from "./../../pages/index";

let prefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? '/'+process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-admin";

const menuKey = process.env.MIX_DEFAULT_MENU
        ? process.env.MIX_DEFAULT_MENU
        : "admin";

export default [
  {
    path: prefix + "/"+ menuKey + "/:slug",
    name: "CrudGeneratedBrowse",
    component: Pages,
    meta: {
      title: "Browse Data",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/add",
    name: "CrudGeneratedAdd",
    component: Pages,
    meta: {
      title: "Add Data",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/sort",
    name: "CrudGeneratedSort",
    component: Pages,
    meta: {
      title: "Sort Data Data",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/:id",
    name: "CrudGeneratedRead",
    component: Pages,
    meta: {
      title: "Detail Data",
    },
  },
  {
    path: prefix + "/"+ menuKey + "/:slug/:id/edit",
    name: "CrudGeneratedEdit",
    component: Pages,
    meta: {
      title: "Edit Data",
    },
  },
];
