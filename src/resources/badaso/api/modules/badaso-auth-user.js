import resource from "../resource";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  user() {
    return resource.get(apiPrefix + "/v1/auth/user");
  },

  changePassword(data) {
    return resource.put(apiPrefix + "/v1/auth/user/change-password", data);
  },

  updateProfile(data) {
    return resource.put(apiPrefix + "/v1/auth/user/profile", data);
  },

  updateEmail(data) {
    return resource.put(apiPrefix + "/v1/auth/user/email", data);
  },

  verifyEmail(data) {
    return resource.post(apiPrefix + "/v1/auth/user/verify-email", data);
  },
};
