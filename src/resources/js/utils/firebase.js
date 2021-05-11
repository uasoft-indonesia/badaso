// add firebase import
import firebase from "firebase/app";
import "firebase/firebase-messaging";
// end firebase import

export const keyMessageNotification = "MessageNotification";
export const keyFCMTokenMessage = "FCMTokenMessage";

export const notificationMessageReceiveHandle = async (app) => {
  try {
    //   initial firebase
    let firebaseMessages = app.$messaging;
    let store = app.$store;

    const handleMessage = (isReadMessage) => (messageData) => {
      let {
        data: { user_name, fcm_message_id },
        notification: { title, body },
      } = messageData;

      app.$vs.notify({
        title,
        text: body,
        color: "primary",
        time: 4000,
      });

      // app.$api.badasoFcm.readMessage(fcm_message_id);
      store.commit("badaso/SET_GLOBAL_STATE", {
        key: "countUnreadMessage",
        value: store.getters["badaso/getGlobalState"].countUnreadMessage + 1,
      });
    };

    firebaseMessages.onMessage(handleMessage(true));
  } catch (error) {}
};
