import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  index(data = {}) {
    let ep = apiPrefix + '/v1/dashboard';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
};
