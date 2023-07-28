import resource from "../resource";
import QueryString from "../query-string";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  browseItemByKey(data) {
    const ep = apiPrefix + "/v1/menus/item-by-key";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  browseItemByKeys(data) {
    const ep = apiPrefix + "/v1/menus/item-by-keys";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  browse(data = {}) {
    const ep = apiPrefix + "/v1/menus";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  read(data) {
    const ep = apiPrefix + "/v1/menus/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  edit(data) {
    return resource.put(apiPrefix + "/v1/menus/edit", data);
  },

  add(data) {
    return resource.post(apiPrefix + "/v1/menus/add", data);
  },

  delete(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/menus/delete", paramData);
  },

  browseItem(data) {
    const ep = apiPrefix + "/v1/menus/item";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  arrangeItems(data) {
    return resource.put(apiPrefix + "/v1/menus/arrange-items", data);
  },

  readItem(data) {
    const ep = apiPrefix + "/v1/menus/item/read";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  editItem(data) {
    return resource.put(apiPrefix + "/v1/menus/item/edit", data);
  },

  editItemOrder(data) {
    return resource.put(apiPrefix + "/v1/menus/item/edit-order", data);
  },

  addItem(data) {
    return resource.post(apiPrefix + "/v1/menus/item/add", data);
  },

  deleteItem(data) {
    const paramData = {
      data: data,
    };
    return resource.delete(apiPrefix + "/v1/menus/item/delete", paramData);
  },

  getItemPermissions(data = {}) {
    const ep = apiPrefix + "/v1/menus/item/permissions";
    const qs = QueryString(data);
    const url = ep + qs;
    return resource.get(url);
  },

  setItemPermissions(data) {
    return resource.put(apiPrefix + "/v1/menus/item/permissions", data);
  },

  menuOptions(data) {
    return resource.put(apiPrefix + "/v1/menus/menu-options", data);
  },
};
