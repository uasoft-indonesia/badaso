import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    let ep = endpoint.database.browse;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = endpoint.database.read;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(endpoint.database.edit, data);
  },

  add(data) {
    return resource.post(endpoint.database.add, data);
  },

  delete(data) {
    let paramData = {
      data: data
    }
    return resource.delete(endpoint.database.delete, paramData);
  },

  browseMigration(data = {}) {
    let ep = endpoint.database.browseMigration;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  rollback(data) {
    return resource.post(endpoint.database.rollback, data);
  },

  check(data = {}) {
    let ep = endpoint.database.check;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  migrate(data = {}) {
    let ep = endpoint.database.migrate;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  deleteMigration(data) {
    return resource.post(endpoint.database.deleteMigration, data);
  },

  getType(data = {}) {
    let ep = endpoint.database.getType;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  }
};
