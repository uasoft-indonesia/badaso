import resource from "../resource";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  login(data) {
    let ep = apiPrefix + '/v1/auth/login';
    let response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        let token = res.data.accessToken;
        localStorage.setItem("token", token);
      }
    });
    return response;
  },

  logout() {
    let ep = apiPrefix + '/v1/auth/logout';
    let response = resource.post(ep);
    response.then((res) => {
      localStorage.clear();
    });
    return response;
  },

  forgotPassword(data) {
    let ep = apiPrefix + '/v1/auth/forgot-password';
    return resource.post(ep, data);
  },

  forgotPasswordVerifyToken(data) {
    let ep = apiPrefix + '/v1/auth/forgot-password-verify';
    return resource.post(ep, data);
  },

  resetPassword(data) {
    let ep = apiPrefix + '/v1/auth/reset-password';
    return resource.post(ep, data);
  },

  reRequestVerificationToken(data) {
    let ep = apiPrefix + '/v1/auth/re-request-verification'
    return resource.post(ep, data);
  },

  register(data) {
    let ep = apiPrefix + '/v1/auth/register'
    let response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        let token = res.data.accessToken;
        localStorage.setItem("token", token);
        let date = new Date();
        let timeNow = date.getTime();
        localStorage.setItem(
          window.btoa("tokenAccessTime"),
          window.btoa(timeNow)
        );
      }
    });
    return response;
  },

  verify(data) {
    let ep = apiPrefix + '/v1/auth/verify'
    let response = resource.post(ep, data);
    response.then((res) => {
      if (res.data.accessToken) {
        let token = res.data.accessToken;
        localStorage.setItem("token", token);
        let date = new Date();
        let timeNow = date.getTime();
        localStorage.setItem(
          window.btoa("tokenAccessTime"),
          window.btoa(timeNow)
        );
      }
    });
    return response;
  },

  refreshToken() {
    let ep = apiPrefix + '/v1/auth/refresh-token'
    let response = resource.post(ep);
    response.then((res) => {
      let token = res.data.accessToken;
      localStorage.setItem("token", token);
    });
    return response;
  },
};
