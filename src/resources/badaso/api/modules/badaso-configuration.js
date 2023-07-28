import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  applyable(data = {}) {
    const ep = apiPrefix + "/v1/configurations/applyable";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  maintenance(data = {}) {
    const ep = apiPrefix + "/v1/configurations/maintenance";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    const ep = apiPrefix + "/v1/configurations";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/configurations/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  fetch(data) {
    const ep = apiPrefix + "/v1/configurations/fetch";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
  fetchMultiple(data) {
    const ep = apiPrefix + "/v1/configurations/fetch-multiple";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/configurations/edit", data);
  },

  editMultiple(data) {
    return resource.put(apiPrefix + "/v1/configurations/edit-multiple", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/configurations/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/configurations/delete", paramData);
  },
};
