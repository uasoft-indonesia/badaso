import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  read(data) {
    let ep = apiPrefix + '/v1/table/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    let ep = apiPrefix + '/v1/table';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  relationDataBySlug(data = {}) {
    let ep = apiPrefix + '/v1/table/relation-data-by-slug';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
};
