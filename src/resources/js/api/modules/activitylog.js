import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    let ep = endpoint.activitylog.browse;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = endpoint.activitylog.read;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
};
