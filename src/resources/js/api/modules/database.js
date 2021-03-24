import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  browse(data = {}) {
    let ep = apiPrefix + '/v1/database';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + '/v1/database/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + '/v1/database/edit', data);
  },

  add(data) {
    return resource.post(apiPrefix + '/v1/database/add', data);
  },

  delete(data) {
    let paramData = {
      data: data
    }
    return resource.delete(apiPrefix + '/v1/database/delete', paramData);
  },

  browseMigration(data = {}) {
    let ep = apiPrefix + '/v1/database/migration/browse';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  rollback(data) {
    return resource.post(apiPrefix + '/v1/database/rollback', data);
  },

  check(data = {}) {
    let ep = apiPrefix + '/v1/database/migration/status';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  migrate(data = {}) {
    let ep = apiPrefix + '/v1/database/migration/migrate';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  deleteMigration(data) {
    return resource.post(apiPrefix + '/v1/database/migration/delete', data);
  },

  getType(data = {}) {
    let ep = apiPrefix + '/v1/database/type';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  }
};
