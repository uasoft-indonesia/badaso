let prefix_env = process.env.MIX_DASHBOARD_ROUTE_PREFIX
  ? process.env.MIX_DASHBOARD_ROUTE_PREFIX
  : "badaso-admin";

let prefix = '/'+prefix_env+'/'

export default [
  {
    url: prefix + "permission",
    name: "Permission Management",
    icon: "lock",
  },
  {
    url: prefix + "role",
    name: "Role Management",
    icon: "accessibility",
  },
  {
    url: prefix + "user",
    name: "User Management",
    icon: "person",
  },
  {
    url: prefix + "menu",
    name: "Menu Management",
    icon: "menu",
  },
  {
    url: prefix + "bread",
    name: "BREAD Management",
    icon: "list_alt",
  },
  {
    url: prefix + "site",
    name: "Site Management",
    icon: "desktop_mac",
  },
];
