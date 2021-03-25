let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

export default [
    // your public route here
    // example
    //   {
    //     path: prefix + "/test",
    //     name: "Test",
    //     component: Test,
    //   },
];
