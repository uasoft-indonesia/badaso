import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  view(file) {
    let data = {
      file,
    };
    let ep = endpoint.file.view;
    let qs = QueryString(data);
    let url = ep + qs;
    return url;
  },

  download(file) {
    let data = {
      file,
    };
    let ep = endpoint.file.download;
    let qs = QueryString(data);
    let url = ep + qs;
    return url;
  },
};
