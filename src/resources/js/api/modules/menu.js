import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
? '/' + process.env.MIX_API_ROUTE_PREFIX
: "/badaso-api";

export default {
  browseItemByKey(data) {
    let ep = apiPrefix + '/v1/menus/item-by-key';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    let ep = apiPrefix + '/v1/menus';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    let ep = apiPrefix + '/v1/menus/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + '/v1/menus/edit', data);
  },

  add(data) {
    return resource.post(apiPrefix + '/v1/menus/add', data);
  },

  delete(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/menus/delete', paramData);
  },

  browseItem(data) {
    let ep = apiPrefix + '/v1/menus/item';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  arrangeItems(data) {
    return resource.put(apiPrefix + '/v1/menus/arrange-items', data);
  },

  readItem(data) {
    let ep = apiPrefix + '/v1/menus/item/read';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },

  editItem(data) {
    return resource.put(apiPrefix + '/v1/menus/item/edit', data);
  },

  editItemOrder(data) {
    return resource.put(apiPrefix + '/v1/menus/item/edit-order', data);
  },

  addItem(data) {
    return resource.post(apiPrefix + '/v1/menus/item/add', data);
  },

  deleteItem(data) {
    let paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + '/v1/menus/item/delete', paramData);
  },

  getItemPermissions(data = {}) {
    let ep = apiPrefix + '/v1/menus/item/permissions';
    let qs = QueryString(data);
    let url = ep + qs;
    return resource.get(url);
  },
  setItemPermissions(data) {
    return resource.put(apiPrefix + '/v1/menus/item/permissions', data);
  },
};
