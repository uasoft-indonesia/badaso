import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    let ep = endpoint.crudManagement.browse;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = endpoint.crudManagement.read;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  readBySlug(data) {
    let ep = endpoint.crudManagement.readBySlug;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(endpoint.crudManagement.edit, data);
  },

  add(data) {
    return resource.post(endpoint.crudManagement.add, data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(endpoint.crudManagement.delete, paramData);
  },
};
