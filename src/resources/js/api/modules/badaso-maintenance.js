import resource from "../resource";

const apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  maintenance(data = {}) {
    return resource.post(apiPrefix + "/v1/maintenance", data);
  },
};
