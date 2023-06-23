import Pages from "./../../pages/index.vue";

const prefix = import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
  : "/badaso-dashboard";
const secretLoginPrefix = import.meta.env.VITE_BADASO_SECRET_LOGIN_PREFIX
  ? "/" + import.meta.env.VITE_BADASO_SECRET_LOGIN_PREFIX
  : "/badaso-secret-login";
export default [
  {
    path: prefix + "/login",
    name: "AuthLogin",
    component: Pages,
    meta: {
      title: "Login",
    },
  },
  {
    path: prefix + "/register",
    name: "AuthRegister",
    component: Pages,
    meta: {
      title: "Register",
    },
  },
  {
    path: prefix + "/forgot-password",
    name: "AuthForgotPassword",
    component: Pages,
    meta: {
      title: "Forgot Password",
    },
  },
  {
    path: prefix + "/reset-password",
    name: "AuthResetPassword",
    component: Pages,
    meta: {
      title: "Reset Password",
    },
  },
  {
    path: prefix + "/verify",
    name: "AuthVerify",
    component: Pages,
    meta: {
      title: "Email Verification",
    },
  },
  {
    path: prefix + secretLoginPrefix,
    name: "SecretLogin",
    component: Pages,
    meta: {
      title: "Secret Login",
    },
  },
];
