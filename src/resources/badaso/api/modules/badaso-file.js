import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  view(file) {
    const data = {
      file,
    };
    const ep = apiPrefix + "/v1/file/view";
    const qs = QueryString(data);
    const url = ep + qs;
    return url;
  },

  upload(files) {
    return resource.post(apiPrefix + "/v1/file/upload", {
      files: files,
    });
  },

  download(file) {
    const data = {
      file,
    };
    const ep = apiPrefix + "/v1/file/download";
    const qs = QueryString(data);
    const url = ep + qs;
    return url;
  },

  browseUsingLfm(data = {}) {
    const ep = apiPrefix + "/v1/file/browse/lfm";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  uploadUsingLfm(files) {
    return resource.post(apiPrefix + "/v1/file/upload/lfm", files);
  },

  deleteUsingLfm(data) {
    const ep = apiPrefix + "/v1/file/delete/lfm";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  browseConfiguration(data = {}) {
    const ep = apiPrefix + "/v1/file/mimetypes";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },
};
