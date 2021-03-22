import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  index(data = {}) {
    let ep = endpoint.dashboard.index;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
};
