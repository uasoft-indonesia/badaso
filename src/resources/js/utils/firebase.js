// add firebase import
import firebase from "firebase/app";
import "firebase/firebase-messaging";
// end firebase import

export const keyMessageNotification = "MessageNotification";
export const keyFCMTokenMessage = "FCMTokenMessage";

export const notificationMessageReceive = async (app) => {
  //   initial firebase
  let firebaseMessages = app.$messaging;

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

    app.$api.badasoFcm.readMessage(fcm_message_id);
  };

  firebaseMessages.onMessage(handleMessage(true));
};
