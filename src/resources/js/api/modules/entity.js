import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.entity + "/" + data.slug;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  read(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.entity + "/" + data.slug + "/read";
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  edit(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.entity + "/" + data.slug + "/edit", data);
    });
  },

  add(data) {
    return auth.refreshToken().then((res) => {
      return resource.post(endpoint.entity + "/" + data.slug + '/add', data);
    });
  },

  delete(data) {
    return auth.refreshToken().then((res) => {
      let paramData = {
        data: data,
      };
      return resource.delete(endpoint.entity + "/" + data.slug + '/delete', paramData);
    });
  },
  deleteMultiple(data) {
    return auth.refreshToken().then((res) => {
      let paramData = {
        data: data,
      };
      return resource.delete(
        endpoint.entity + "/" + data.slug + '/delete-multiple',
        paramData
      );
    });
  },
};
