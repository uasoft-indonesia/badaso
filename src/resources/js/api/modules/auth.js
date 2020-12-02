import resource from "../resource";
import endpoint from "../endpoint";

export default {
  login(data) {
    let response = resource.post(endpoint.auth.login, data);
    response.then((res) => {
      if (res.success) {
        let token = res.data_detail.access_token;
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

  logout() {
    let response = resource.post(endpoint.auth.logout);
    response.then((res) => {
      if (res.success) {
        localStorage.clear();
      }
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
    return resource.post(endpoint.auth.register, data);
  },

  verify(data) {
    return resource.post(endpoint.auth.verify, data);
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
        if (res.success) {
          let token = res.data_detail.access_token;
          localStorage.setItem("token", token);
          localStorage.setItem(
            window.btoa("tokenAccessTime"),
            window.btoa(timeNow)
          );
        }
      });
      return response;
    } else {
      let accessTime = window.atob(lastTokenAccessTime);
      if (timeNow - accessTime > 3000000) {
        let response = resource.post(endpoint.auth.refreshToken);
        response.then((res) => {
          if (res.success) {
            let token = res.data_detail.access_token;
            localStorage.setItem("token", token);
            localStorage.setItem(
              window.btoa("tokenAccessTime"),
              window.btoa(timeNow)
            );
          }
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
