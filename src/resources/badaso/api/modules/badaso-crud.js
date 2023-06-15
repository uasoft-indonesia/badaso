import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  browse(data = {}) {
    const ep = apiPrefix + "/v1/crud";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/crud/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  readBySlug(data) {
    const ep = apiPrefix + "/v1/crud/read-by-slug";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/crud/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/crud/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/crud/delete", paramData);
  },
};
