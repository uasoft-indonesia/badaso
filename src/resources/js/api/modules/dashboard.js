import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  index(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.dashboard.index;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },
};
