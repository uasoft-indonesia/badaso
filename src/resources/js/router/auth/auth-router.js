import Login from "./../../pages/auth/login.vue";
import Register from "./../../pages/auth/register.vue";
import Verify from "./../../pages/auth/verify.vue";
import ForgotPassword from "./../../pages/auth/forgot-password.vue";
import ResetPassword from "./../../pages/auth/reset-password.vue";

let prefix_env = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin";

let prefix = "/" + prefix_env;

export default [
    {
        path: prefix + "/login",
        name: "Login",
        component: Login,
        meta: {
          title: "Login",
        },
      },
      {
        path: prefix + "/register",
        name: "Register",
        component: Register,
        meta: {
          title: "Register",
        },
      },
      {
        path: prefix + "/forgot-password",
        name: "ForgotPassword",
        component: ForgotPassword,
        meta: {
          title: "Forgot Password",
        },
      },
      {
        path: prefix + "/reset-password",
        name: "ResetPassword",
        component: ResetPassword,
        meta: {
          title: "Reset Password",
        },
      },
      {
        path: prefix + "/verify",
        name: "Verify",
        component: Verify,
        meta: {
          title: "Email Verification",
        },
      },
];
