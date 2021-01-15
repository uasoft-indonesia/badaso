import ActivityLogBrowse from './../views/activity-log/browse'
import ActivityLogRead from './../views/activity-log/read'

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

export default [
    // your public route here
    // example
    //   {
    //     path: "PageUrl",
    //     name: "PageName",
    //     component: PageComponent,
    //   },
    {
        path: prefix + "/activitylog",
        name: "ActivityLogBrowse",
        component: ActivityLogBrowse,
        meta: {
          title: "Browse Activity Log",
        },
      },
      {
        path: prefix + "/activitylog/:id/detail",
        name: "ActivityLogRead",
        component: ActivityLogRead,
        meta: {
          title: "Detail Activity Log",
        },
      },
];
