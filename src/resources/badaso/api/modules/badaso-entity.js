import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX
  : "/badaso-api";

const entity_prefix = apiPrefix + "/v1/entities";

export default {
  browse(data = {}) {
    const ep = entity_prefix + "/" + data.slug;
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  all(data = {}) {
    const ep = entity_prefix + "/" + data.slug + "/all";
    const url = ep;
    return resource.get(url);
  },

  read(data) {
    const ep = entity_prefix + "/" + data.slug + "/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(entity_prefix + "/" + data.slug + "/edit", {
      data: data.data,
    });
  },

  add(data) {
    return resource.post(entity_prefix + "/" + data.slug + "/add", {
      data: data.data,
    });
  },

  restore(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entity_prefix + "/" + data.slug + "/restore",
      paramData
    );
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entity_prefix + "/" + data.slug + "/delete",
      paramData
    );
  },

  deleteMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entity_prefix + "/" + data.slug + "/delete-multiple",
      paramData
    );
  },

  restoreMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entity_prefix + "/" + data.slug + "/restore-multiple",
      paramData
    );
  },

  sort(data) {
    return resource.put(entity_prefix + "/" + data.slug + "/sort", data);
  },
  maintenance(data = {}) {
    return resource.post(
      entity_prefix + "/" + data.slug + "/maintenance",
      data
    );
  },
};
