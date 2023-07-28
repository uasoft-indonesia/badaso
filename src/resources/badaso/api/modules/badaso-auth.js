import resource from "../resource";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";
const secretLoginPrefix = import.meta.env.VITE_BADASO_SECRET_LOGIN_PREFIX
  ? "/" + import.meta.env.VITE_BADASO_SECRET_LOGIN_PREFIX
  : "/badaso-secret-login";

export default {
  secretLogin(data) {
    const ep = apiPrefix + "/v1/auth" + secretLoginPrefix;
    const response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        const token = res.data.accessToken;
        localStorage.setItem("token", token);
      }
    });
    return response;
  },

  login(data) {
    const ep = apiPrefix + "/v1/auth/login";
    const response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        const token = res.data.accessToken;
        localStorage.setItem("token", token);
      }
    });
    return response;
  },

  logout() {
    const ep = apiPrefix + "/v1/auth/logout";
    const response = resource.post(ep);
    response.then((res) => {
      localStorage.clear();
    });
    return response;
  },

  forgotPassword(data) {
    const ep = apiPrefix + "/v1/auth/forgot-password";
    return resource.post(ep, data);
  },

  forgotPasswordVerifyToken(data) {
    const ep = apiPrefix + "/v1/auth/forgot-password-verify";
    return resource.post(ep, data);
  },

  resetPassword(data) {
    const ep = apiPrefix + "/v1/auth/reset-password";
    return resource.post(ep, data);
  },

  reRequestVerificationToken(data) {
    const ep = apiPrefix + "/v1/auth/re-request-verification";
    return resource.post(ep, data);
  },

  register(data) {
    const ep = apiPrefix + "/v1/auth/register";
    const response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        const token = res.data.accessToken;
        localStorage.setItem("token", token);
        const date = new Date();
        const timeNow = date.getTime();
        localStorage.setItem(
          window.btoa("tokenAccessTime"),
          window.btoa(timeNow)
        );
      }
    });
    return response;
  },

  verify(data) {
    const ep = apiPrefix + "/v1/auth/verify";
    const response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        const token = res.data.accessToken;
        localStorage.setItem("token", token);
        const date = new Date();
        const timeNow = date.getTime();
        localStorage.setItem(
          window.btoa("tokenAccessTime"),
          window.btoa(timeNow)
        );
      }
    });
    return response;
  },

  refreshToken() {
    const ep = apiPrefix + "/v1/auth/refresh-token";
    const response = resource.post(ep);
    response.then((res) => {
      const token = res.data.accessToken;
      localStorage.setItem("token", token);
    });
    return response;
  },
};
