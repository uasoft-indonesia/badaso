import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  applyable(data = {}) {
    let ep = apiPrefix + '/v1/configurations/applyable';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    let ep = apiPrefix + '/v1/configurations';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + '/v1/configurations/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + '/v1/configurations/edit', data);
  },

  editMultiple(data) {
    return resource.put(apiPrefix + '/v1/configurations/edit-multiple', data);
  },

  add(data) {
    return resource.post(apiPrefix + '/v1/configurations/add', data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/configurations/delete', paramData);
  },
};
