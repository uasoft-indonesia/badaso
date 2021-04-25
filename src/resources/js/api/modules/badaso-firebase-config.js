import resource from "../resource";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  get() {
    let url = `${apiPrefix}/firebase/config`;
    return resource.get(url);
  },
};
