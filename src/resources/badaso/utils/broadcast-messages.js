export const BROADCAST_TYPE_ONLINE_STATUS = "BROADCAST_TYPE_ONLINE_STATUS";
export const BROADCAST_TYPE_FIREBASE_MESSAGE =
  "BROADCAST_TYPE_FIREBASE_MESSAGE";

export const broadcastMessageHandle = (app) => {
  try {
    const broadcastChannel = app.$broadcastChannel;

    if (!broadcastChannel) return;

    const store = app.$store;

    let isOnline;

    broadcastChannel.addEventListener("message", (event) => {
      const dataMessage = event.data;

      if (dataMessage.type) {
        switch (dataMessage.type) {
          case BROADCAST_TYPE_ONLINE_STATUS:
            isOnline = dataMessage.data.status && navigator.onLine;
            store.commit("badaso/SET_GLOBAL_STATE", {
              key: "isOnline",
              value: isOnline,
            });
            break;

          case BROADCAST_TYPE_FIREBASE_MESSAGE:
            store.commit("badaso/SET_GLOBAL_STATE", {
              key: "countUnreadMessage",
              value:
                store.getters["badaso/getGlobalState"].countUnreadMessage + 1,
            });
            break;

          default:
            break;
        }
      }
    });
  } catch (error) {
    console.error("Broadcast message handle error ", error);
  }
};
