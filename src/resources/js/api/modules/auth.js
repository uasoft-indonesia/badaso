import resource from "../resource";
import endpoint from "../endpoint";

export default {
  login(data) {
    let response = resource.post(endpoint.auth.login, data);
    response.then((res) => {
      if (res.data.accessToken) {
        let token = res.data.accessToken;
        localStorage.setItem("token", token);
      }
    });
    return response;
  },

  logout() {
    let response = resource.post(endpoint.auth.logout);
    response.then((res) => {
      localStorage.clear();
    });
    return response;
  },

  forgotPassword(data) {
    return resource.post(endpoint.auth.forgotPassword, data);
  },

  forgotPasswordVerifyToken(data) {
    return resource.post(endpoint.auth.forgotPasswordVerifyToken, data);
  },

  resetPassword(data) {
    return resource.post(endpoint.auth.resetPassword, data);
  },

  reRequestVerificationToken(data) {
    return resource.post(endpoint.auth.reRequestVerificationToken, data);
  },

  register(data) {
    let response = resource.post(endpoint.auth.register, data);
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
    let response = resource.post(endpoint.auth.verify, data);
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
    let response = resource.post(endpoint.auth.refreshToken);
    response.then((res) => {
      let token = res.data.accessToken;
      localStorage.setItem("token", token);
    });
    return response;
  },

  user() {
    return resource.post(endpoint.auth.user);
  },

  changePassword(data) {
    return resource.post(endpoint.auth.changePassword, data);
  },
};
