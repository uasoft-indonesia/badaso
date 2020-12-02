import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  component(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.data.component;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  filterOperator(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.data.filterOperator;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },
};
