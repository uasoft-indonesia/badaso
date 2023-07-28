import Pages from "./../../pages/index.vue";

const prefix = import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-dashboard";

export default [
  {
    path: prefix,
    redirect: prefix + "/home",
  },
  {
    path: prefix + "/admin",
    redirect: prefix + "/home",
  },
  {
    path: prefix + "/home",
    name: "Home",
    component: Pages,
    meta: {
      title: "Home",
    },
  },
  {
    path: prefix + "/profile",
    name: "UserProfile",
    component: Pages,
    meta: {
      title: "User Profile",
    },
  },
];
