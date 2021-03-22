import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    let ep = endpoint.user.browse;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = endpoint.user.read;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(endpoint.user.edit, data);
  },

  add(data) {
    return resource.post(endpoint.user.add, data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(endpoint.user.delete, paramData);
  },
  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(endpoint.user.deleteMultiple, paramData);
  },
  roles(data = {}) {
    let ep = endpoint.user.roles;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
  addRoles(data) {
    return resource.post(endpoint.user.addRoles, data);
  },
};
