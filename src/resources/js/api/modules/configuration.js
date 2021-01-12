import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  applyable(data = {}) {
    let ep = endpoint.configuration.applyable;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.configuration.browse;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  read(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.configuration.read;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  edit(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.configuration.edit, data);
    });
  },

  editMultiple(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.configuration.editMultiple, data);
    });
  },

  add(data) {
    return auth.refreshToken().then((res) => {
      return resource.post(endpoint.configuration.add, data);
    });
  },

  delete(data) {
    return auth.refreshToken().then((res) => {
        let paramData = {
            data: data
        }
      return resource.delete(endpoint.configuration.delete, paramData);
    });
  },
};
