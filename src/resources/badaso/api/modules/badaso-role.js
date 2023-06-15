import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  browse(data = {}) {
    const ep = apiPrefix + "/v1/roles";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/roles/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/roles/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/roles/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/roles/delete", paramData);
  },

  deleteMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/roles/delete-multiple", paramData);
  },

  permissions(data = {}) {
    const ep = apiPrefix + "/v1/role-permissions/all-permission";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
  addPermissions(data) {
    return resource.post(apiPrefix + "/v1/role-permissions/add-edit", data);
  },
};
