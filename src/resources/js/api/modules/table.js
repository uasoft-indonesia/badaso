import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  read(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.table.read;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  browse(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.table.browse;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  relationDataBySlug(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.table.relationDataBySlug;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },
};
