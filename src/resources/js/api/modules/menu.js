import resource from "../resource";
import auth from "./auth";
import endpoint from "../endpoint";
import QueryString from "../query-string";

export default {
  browse(data = {}) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.menu.browse;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  read(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.menu.read;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  edit(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.menu.edit, data);
    });
  },

  add(data) {
    return auth.refreshToken().then((res) => {
      return resource.post(endpoint.menu.add, data);
    });
  },

  delete(data) {
    return auth.refreshToken().then((res) => {
      let paramData = {
        data: data
    }
      return resource.delete(endpoint.menu.delete, paramData);
    });
  },

  browseItem(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.menu.browseItem;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  browseItemByKey(data) {
    return auth.refreshToken().then((res) => {
      let ep = endpoint.menu.browseItemByKey;
      let qs = QueryString(data);
      let url = ep + qs;
      return resource.get(url);
    });
  },

  readItem(data) {
    return auth.refreshToken().then((res) => {
      let ep = ep.menu.readItem;
      let qs = QueryString(data);
      let url = endpoint + qs;
      return resource.get(url);
    });
  },

  editItem(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.menu.editItem, data);
    });
  },

  editItemOrder(data) {
    return auth.refreshToken().then((res) => {
      return resource.put(endpoint.menu.editItemOrder, data);
    });
  },

  addItem(data) {
    return auth.refreshToken().then((res) => {
      return resource.post(endpoint.menu.addItem, data);
    });
  },

  deleteItem(data) {
    return auth.refreshToken().then((res) => {
      let paramData = {
        data: data
    }
      return resource.delete(endpoint.menu.deleteItem, paramData);
    });
  },
};
