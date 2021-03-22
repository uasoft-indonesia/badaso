import resource from "../resource";
import endpoint from "../endpoint";

export default {
  user() {
    return resource.get(endpoint.authuser.user);
  },

  changePassword(data) {
    return resource.put(endpoint.authuser.changePassword, data);
  },

  updateProfile(data) {
    return resource.put(endpoint.authuser.updateProfile, data);
  },

  updateEmail(data) {
    return resource.put(endpoint.authuser.updateEmail, data);
  },

  verifyEmail(data) {
    return resource.post(endpoint.authuser.verifyEmail, data);
  },
};
