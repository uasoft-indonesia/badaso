import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  index(data = {}) {
    const ep = apiPrefix + "/v1/dashboard";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
};
