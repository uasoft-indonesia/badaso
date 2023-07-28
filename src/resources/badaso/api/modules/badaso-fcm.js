import resource from "../resource";

const apiPrefix = import.meta.env.VITE_API_ROUTE_PREFIX
  ? "/" + import.meta.env.VITE_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  saveTokenMessage(firebaseTokenMessages) {
    const url = `${apiPrefix}/v1/firebase/cloud_messages/save-token-messages`;
    return resource.put(url, {
      token_get_message: firebaseTokenMessages,
    });
  },
  getMessages() {
    const url = `${apiPrefix}/v1/firebase/messages`;
    return resource.get(url);
  },
  readMessage(id) {
    const url = `${apiPrefix}/v1/firebase/messages/${id}`;
    return resource.put(url);
  },
  getCountUnreadMessage() {
    const url = `${apiPrefix}/v1/firebase/messages/count-unread`;
    return resource.get(url);
  },
};
