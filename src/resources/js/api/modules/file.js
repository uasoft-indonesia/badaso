export default {
  view(file) {
    let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
      ? process.env.MIX_API_ROUTE_PREFIX
      : "badaso-api";

    return "/" + apiPrefix + "/v1/file/view?file=" + file;
  },

  download(file) {
    let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
      ? process.env.MIX_API_ROUTE_PREFIX
      : "badaso-api";

    return "/" + apiPrefix + "/v1/file/download?file=" + file;
  },
};
