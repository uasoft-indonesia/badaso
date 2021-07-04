import Pages from "./../../pages/index";

let prefix = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? "/" + process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-dashboard";

export default [
  {
    path: prefix + "/admin/:slug",
    name: "CrudGeneratedBrowse",
    component: Pages,
    meta: {
      title: "Browse Data",
    },
  },
  {
    path: prefix + "/admin/:slug/bin",
    name: "CrudGeneratedBrowseBin",
    component: Pages,
    meta: {
      title: "Browse Recycle",
    },
  },
  {
    path: prefix + "/admin/:slug/add",
    name: "CrudGeneratedAdd",
    component: Pages,
    meta: {
      title: "Add Data",
    },
  },
  {
    path: prefix + "/admin/:slug/sort",
    name: "CrudGeneratedSort",
    component: Pages,
    meta: {
      title: "Sort Data Data",
    },
  },
  {
    path: prefix + "/admin/:slug/:id",
    name: "CrudGeneratedRead",
    component: Pages,
    meta: {
      title: "Detail Data",
    },
  },
  {
    path: prefix + "/admin/:slug/:id/edit",
    name: "CrudGeneratedEdit",
    component: Pages,
    meta: {
      title: "Edit Data",
    },
  },
];
