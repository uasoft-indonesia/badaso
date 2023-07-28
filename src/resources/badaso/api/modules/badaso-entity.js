import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

const entityPrefix = apiPrefix + "/v1/entities";

export default {
  browse(data = {}) {
    const ep = entityPrefix + "/" + data.slug;
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  all(data = {}) {
    const ep = entityPrefix + "/" + data.slug + "/all";
    const url = ep;
    return resource.get(url);
  },

  read(data) {
    const ep = entityPrefix + "/" + data.slug + "/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(entityPrefix + "/" + data.slug + "/edit", {
      data: data.data,
    });
  },

  add(data) {
    return resource.post(entityPrefix + "/" + data.slug + "/add", {
      data: data.data,
    });
  },

  restore(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entityPrefix + "/" + data.slug + "/restore",
      paramData
    );
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entityPrefix + "/" + data.slug + "/delete",
      paramData
    );
  },

  deleteMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entityPrefix + "/" + data.slug + "/delete-multiple",
      paramData
    );
  },

  restoreMultiple(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(
      entityPrefix + "/" + data.slug + "/restore-multiple",
      paramData
    );
  },

  sort(data) {
    return resource.put(entityPrefix + "/" + data.slug + "/sort", data);
  },
  maintenance(data = {}) {
    return resource.post(entityPrefix + "/" + data.slug + "/maintenance", data);
  },
};
