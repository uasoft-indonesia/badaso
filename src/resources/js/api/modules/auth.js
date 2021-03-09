import resource from "../resource";
import endpoint from "../endpoint";

export default {
  login(data) {
    let response = resource.post(endpoint.auth.login, data);
    response.then((res) => {
        let token = res.data.accessToken;
        localStorage.setItem("token", token);
        let date = new Date();
        let timeNow = date.getTime();
        localStorage.setItem(
          window.btoa("tokenAccessTime"),
          window.btoa(timeNow)
        );
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

  resetPassword(data) {
    return resource.post(endpoint.auth.resetPassword, data);
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
    let date = new Date();
    let timeNow = date.getTime();
    let lastTokenAccessTime = localStorage.getItem(
      window.btoa("tokenAccessTime")
    );
    if (lastTokenAccessTime == null) {
      let response = resource.post(endpoint.auth.refreshToken);
      response.then((res) => {
          let token = res.data.accessToken;
          localStorage.setItem("token", token);
          localStorage.setItem(
            window.btoa("tokenAccessTime"),
            window.btoa(timeNow)
          );
      });
      return response;
    } else {
      let accessTime = window.atob(lastTokenAccessTime);
      if (timeNow - accessTime > 3000000) {
        let response = resource.post(endpoint.auth.refreshToken);
        response.then((res) => {
            let token = res.data.accessToken;
            localStorage.setItem("token", token);
            localStorage.setItem(
              window.btoa("tokenAccessTime"),
              window.btoa(timeNow)
            );
        });
        return response;
      }
    }

    return Promise.resolve("TOKEN ALIVE")
  },

  user() {
    return resource.post(endpoint.auth.user);
  },

  changePassword(data) {
    return resource.post(endpoint.auth.changePassword, data);
  },
};
