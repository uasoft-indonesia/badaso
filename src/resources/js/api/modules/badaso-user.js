import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  browse(data = {}) {
    let ep = apiPrefix + '/v1/users';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + '/v1/users/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + '/v1/users/edit', data);
  },

  add(data) {
    return resource.post(apiPrefix + '/v1/users/add', data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/users/delete', paramData);
  },
  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/users/delete-multiple', paramData);
  },
  roles(data = {}) {
    let ep = apiPrefix + '/v1/user-roles/all-role';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
  addRoles(data) {
    return resource.post(apiPrefix + '/v1/user-roles/add-edit', data);
  },
};
