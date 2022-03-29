import Pages from "./../../pages/index.vue";

const adminPanelRoutePrefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? "/" + process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-dashboard";
const defaultMenuPrefix = process.env.MIX_DEFAULT_MENU
  ? "/" + process.env.MIX_DEFAULT_MENU
  : "/general";
const prefix = `${adminPanelRoutePrefix}${defaultMenuPrefix}`;

export default [
  {
    path: prefix + "/:slug",
    name: "CrudGeneratedBrowse",
    component: Pages,
    meta: {
      title: "Browse Data",
    },
  },
  {
    path: prefix + "/:slug/bin",
    name: "CrudGeneratedBrowseBin",
    component: Pages,
    meta: {
      title: "Browse Recycle",
    },
  },
  {
    path: prefix + "/:slug/add",
    name: "CrudGeneratedAdd",
    component: Pages,
    meta: {
      title: "Add Data",
    },
  },
  {
    path: prefix + "/:slug/sort",
    name: "CrudGeneratedSort",
    component: Pages,
    meta: {
      title: "Sort Data Data",
    },
  },
  {
    path: prefix + "/:slug/:id",
    name: "CrudGeneratedRead",
    component: Pages,
    meta: {
      title: "Detail Data",
    },
  },
  {
    path: prefix + "/:slug/:id/edit",
    name: "CrudGeneratedEdit",
    component: Pages,
    meta: {
      title: "Edit Data",
    },
  },
];
