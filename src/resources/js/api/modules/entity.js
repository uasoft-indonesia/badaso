import resource from "../resource";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    let ep = endpoint.entity + "/" + data.slug;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  all(data = {}) {
    let ep = endpoint.entity + "/" + data.slug + "/all";
    let url = ep;
    return resource.get(url);
  },

  read(data) {
    let ep = endpoint.entity + "/" + data.slug + "/read";
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(endpoint.entity + "/" + data.slug + "/edit", data);
  },

  add(data) {
    return resource.post(endpoint.entity + "/" + data.slug + "/add", data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(
      endpoint.entity + "/" + data.slug + "/delete",
      paramData
    );
  },
  deleteMultiple(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(
      endpoint.entity + "/" + data.slug + "/delete-multiple",
      paramData
    );
  },
  sort(data) {
    return resource.put(endpoint.entity + "/" + data.slug + "/sort", data);
  },
};
