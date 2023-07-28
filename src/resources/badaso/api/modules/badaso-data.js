import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  component(data = {}) {
    const ep = apiPrefix + "/v1/data/components";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  filterOperator(data = {}) {
    const ep = apiPrefix + "/v1/data/filter-operators";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  tableRelations(data = {}) {
    const ep = apiPrefix + "/v1/data/table-relations";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
  configurationGroups(data = {}) {
    const ep = apiPrefix + "/v1/data/configuration-groups";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
};
