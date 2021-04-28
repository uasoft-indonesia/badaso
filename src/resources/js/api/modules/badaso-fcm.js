import resource from "../resource";
import QueryString from "../query-string";

let apiPrefix = process.env.MIX_API_ROUTE_PREFIX
  ? "/" + process.env.MIX_API_ROUTE_PREFIX
  : "/badaso-api";

export default {
  saveTokenMessage(firebaseTokenMessages) {
    let url = `${apiPrefix}/v1/firebase/cloud_messages/save-token-messages`;
    return resource.put(url, {
      token_get_message: firebaseTokenMessages,
    });
  },
  getMessages() {
    let url = `${apiPrefix}/v1/firebase/messages`;
    return resource.get(url);
  },
  readMessage(id){
    let url = `${apiPrefix}/v1/firebase/messages/${id}`;
    return resource.put(url);
  }
};
