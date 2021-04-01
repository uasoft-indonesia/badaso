import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  browse(data = {}) {
    let ep = apiPrefix + '/v1/roles';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + '/v1/roles/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + '/v1/roles/edit', data);
  },

  add(data) {
    return resource.post(apiPrefix + '/v1/roles/add', data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/roles/delete', paramData);
  },

  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/roles/delete-multiple', paramData);
  },

  permissions(data = {}) {
    let ep = apiPrefix + '/v1/role-permissions/all-permission';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
  addPermissions(data) {
    return resource.post(apiPrefix + '/v1/role-permissions/add-edit', data);
  },
};
