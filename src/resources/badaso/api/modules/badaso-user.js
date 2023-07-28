import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  browse(data = {}) {
    const ep = apiPrefix + "/v1/users";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/users/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/users/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/users/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/users/delete", paramData);
  },
  deleteMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/users/delete-multiple", paramData);
  },
  roles(data = {}) {
    const ep = apiPrefix + "/v1/user-roles/all-role";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
  addRoles(data) {
    return resource.post(apiPrefix + "/v1/user-roles/add-edit", data);
  },
};
