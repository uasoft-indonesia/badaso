import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  component(data = {}) {
    let ep = apiPrefix + '/v1/data/components';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  filterOperator(data = {}) {
    let ep = apiPrefix + '/v1/data/filter-operators';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  tableRelations(data = {}) {
    let ep = apiPrefix + '/v1/data/table-relations';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
  configurationGroups(data = {}) {
    let ep = apiPrefix + '/v1/data/configuration-groups';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
};
