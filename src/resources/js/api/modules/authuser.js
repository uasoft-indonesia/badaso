import resource from "../resource";
import endpoint from "../endpoint";
import auth from "./auth";

export default {
  user() {
    return resource.get(endpoint.authuser.user);
  },

  changePassword(data) {
    return auth.refreshToken().then((res) => {
        return resource.put(endpoint.authuser.changePassword, data);
      });
  },

  updateProfile(data) {
    return auth.refreshToken().then((res) => {
        return resource.put(endpoint.authuser.updateProfile, data);
      });
  },

  updateEmail(data) {
    return auth.refreshToken().then((res) => {
        return resource.put(endpoint.authuser.updateEmail, data);
      });
  },

  verifyEmail(data) {
    return auth.refreshToken().then((res) => {
        return resource.post(endpoint.authuser.verifyEmail, data);
      });
  },
};
