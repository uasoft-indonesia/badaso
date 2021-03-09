import Login from "./../views/auth/login.vue";
import Register from "./../views/auth/register.vue";
import Verify from "./../views/auth/verify.vue";
import ForgotPassword from "./../views/auth/forgot-password.vue";
import ResetPassword from "./../views/auth/reset-password.vue";

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

export default [
    {
        path: prefix + "/login",
        name: "Login",
        component: Login,
      },
      {
        path: prefix + "/register",
        name: "Register",
        component: Register,
      },
      {
        path: prefix + "/forgot-password",
        name: "ForgotPassword",
        component: ForgotPassword,
      },
      {
        path: prefix + "/reset-password",
        name: "ResetPassword",
        component: ResetPassword,
      },
      {
        path: prefix + "/verify",
        name: "Verify",
        component: Verify,
      },
];
