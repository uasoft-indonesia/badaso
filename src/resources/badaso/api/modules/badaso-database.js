import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  browse(data = {}) {
    const ep = apiPrefix + "/v1/database";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/database/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/database/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/database/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/database/delete", paramData);
  },

  browseMigration(data = {}) {
    const ep = apiPrefix + "/v1/database/migration/browse";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  rollback(data) {
    return resource.post(apiPrefix + "/v1/database/rollback", data);
  },

  check(data = {}) {
    const ep = apiPrefix + "/v1/database/migration/status";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  migrate(data = {}) {
    const ep = apiPrefix + "/v1/database/migration/migrate";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  deleteMigration(data) {
    return resource.post(apiPrefix + "/v1/database/migration/delete", data);
  },

  getType(data = {}) {
    const ep = apiPrefix + "/v1/database/type";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
};
