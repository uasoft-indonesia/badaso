import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";
import { param } from "jquery";

export default {
  browse(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.user.browse;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  read(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.user.read;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  edit(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.user.edit, data);
    });
  },

  add(data) {
    return auth.refreshToken().then((res) => {
      return resource.post(endpoint.user.add, data);
    });
  },

  delete(data) {
    return auth.refreshToken().then((res) => {
        let paramData = {
            data: data
        }
      return resource.delete(endpoint.user.delete, paramData);
    });
  },
  deleteMultiple(data) {
    return auth.refreshToken().then((res) => {
        let paramData = {
            data: data
        }
      return resource.delete(endpoint.user.deleteMultiple, paramData);
    });
  },
  roles(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.user.roles;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },
  addRoles(data) {
    return auth.refreshToken().then((res) => {
      return resource.post(endpoint.user.addRoles, data);
    });
  },
};
