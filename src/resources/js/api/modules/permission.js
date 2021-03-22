import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    let ep = endpoint.permission.browse;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = endpoint.permission.read;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(endpoint.permission.edit, data);
  },

  add(data) {
    return resource.post(endpoint.permission.add, data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(endpoint.permission.delete, paramData);
  },

  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(endpoint.permission.deleteMultiple, paramData);
  },
};
