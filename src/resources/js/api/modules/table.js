import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  read(data) {
    let ep = endpoint.table.read;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    let ep = endpoint.table.browse;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  relationDataBySlug(data = {}) {
    let ep = endpoint.table.relationDataBySlug;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
};
