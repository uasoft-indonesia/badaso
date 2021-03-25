import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

const entity_prefix = apiPrefix + '/v1/entities';

export default {
  browse(data = {}) {
    let ep = entity_prefix + "/" + data.slug;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  all(data = {}) {
    let ep = entity_prefix + "/" + data.slug + "/all";
    let url = ep;
    return resource.get(url);
  },

  read(data) {
    let ep = entity_prefix + "/" + data.slug + "/read";
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(entity_prefix + "/" + data.slug + "/edit", data);
  },

  add(data) {
    return resource.post(entity_prefix + "/" + data.slug + "/add", data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(
      entity_prefix + "/" + data.slug + "/delete",
      paramData
    );
  },
  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(
      entity_prefix + "/" + data.slug + "/delete-multiple",
      paramData
    );
  },
  sort(data) {
    return resource.put(entity_prefix + "/" + data.slug + "/sort", data);
  },
};
