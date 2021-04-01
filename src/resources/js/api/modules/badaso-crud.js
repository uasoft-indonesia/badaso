import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  browse(data = {}) {
    let ep = apiPrefix + '/v1/crud';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + '/v1/crud/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  readBySlug(data) {
    let ep = apiPrefix + '/v1/crud/read-by-slug';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + '/v1/crud/edit', data);
  },

  add(data) {
    return resource.post(apiPrefix + '/v1/crud/add', data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/crud/delete', paramData);
  },
};
