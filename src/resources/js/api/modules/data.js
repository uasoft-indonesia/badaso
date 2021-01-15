import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  component(data = {}) {
    let ep = endpoint.data.component;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  filterOperator(data = {}) {
    let ep = endpoint.data.filterOperator;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  tableRelations(data = {}) {
    let ep = endpoint.data.tableRelations;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
  configurationGroups(data = {}) {
    let ep = endpoint.data.configurationGroups;
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  }
};
