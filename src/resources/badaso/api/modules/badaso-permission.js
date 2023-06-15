import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  browse(data = {}) {
    const ep = apiPrefix + "/v1/permissions";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/permissions/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/permissions/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/permissions/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/permissions/delete", paramData);
  },

  deleteMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      apiPrefix + "/v1/permissions/delete-multiple",
      paramData
    );
  },
};
