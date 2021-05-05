import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  view(file) {
    let data = {
      file,
    };
    let ep = apiPrefix + "/v1/file/view";
    let qs = QueryString(data);
    let url = ep + qs;
    return url;
  },

  upload(files) {
    return resource.post(apiPrefix + "/v1/file/upload", {
      files: files
    });
  },

  download(file) {
    let data = {
      file,
    };
    let ep = apiPrefix + "/v1/file/download";
    let qs = QueryString(data);
    let url = ep + qs;
    return url;
  },
};
