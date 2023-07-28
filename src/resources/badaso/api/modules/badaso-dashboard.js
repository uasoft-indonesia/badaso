import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  index(data = {}) {
    const ep = apiPrefix + "/v1/dashboard";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
};
