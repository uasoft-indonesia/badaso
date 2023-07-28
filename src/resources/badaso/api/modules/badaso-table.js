import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  getDataType(data) {
    const ep = apiPrefix + "/v1/table/data-type";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/table/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    const ep = apiPrefix + "/v1/table";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  relationDataBySlug(data = {}) {
    const ep = apiPrefix + "/v1/table/relation-data-by-slug";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
};
