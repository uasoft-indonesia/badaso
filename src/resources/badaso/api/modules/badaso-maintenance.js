import resource from "../resource";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  maintenance(data = {}) {
    return resource.post(apiPrefix + "/v1/maintenance", data);
  },
};
