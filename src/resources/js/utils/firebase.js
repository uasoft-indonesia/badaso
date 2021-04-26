// add firebase import
import firebase from "firebase/app";
import "firebase/firebase-messaging";
// end firebase import

export const keyMessageNotification = "MessageNotification";
export const keyFCMTokenMessage = "FCMTokenMessage";

// add config firebase
export const initializationFCM = () => {
  var firebaseConfig = {
    apiKey: process.env.MIX_FIREBASE_API_KEY,
    authDomain: process.env.MIX_FIREBASE_AUTH_DOMAIN,
    projectId: process.env.MIX_FIREBASE_PROJECT_ID,
    storageBucket: process.env.MIX_FIREBASE_STORAGE_BUCKET,
    messagingSenderId: process.env.MIX_FIREBASE_MESSAGE_SEENDER,
    appId: process.env.MIX_FIREBASE_APP_ID,
    measurementId: process.env.MIX_FIREBASE_MEASUREMENT_ID,
  };
  // end add config firebase

  // initialization firebase

  if (!firebase.apps.length) {
    let host = window.location.origin;
    let firebaseMessageServiceWorkerLocation = `${host}/firebase-messaging-sw.js`;

    // add to service woker
    navigator.serviceWorker
      .register(firebaseMessageServiceWorkerLocation)
      .then((error) => {
        const msg = `Service Worker Error (${error})`;
        console.error(msg);
      });
    firebase.initializeApp(firebaseConfig);
  } else {
    firebase.app();
  }

  return firebase.messaging();
};

export const firebaseMessageReady = async () => {
  try {
    //   initial firebase
    let firebaseMessages = initializationFCM();

    //   get token firebase cloud message
    let vapidKey = process.env.MIX_FIREBASE_WEB_PUSH_CERTIFICATES;
    let tokenMessage = await firebaseMessages.getToken({ vapidKey });

    //   result message
    return { firebaseMessages, tokenMessage };
  } catch (error) {
    return {};
  }
};

export const notificationMessageReceive = async () => {
  //   initial firebase
  let firebaseMessages = initializationFCM();

  let messageNotification = localStorage.getItem(keyMessageNotification);
  if (messageNotification == null) messageNotification = [];
  else messageNotification = JSON.parse(messageNotification);

  const handleMessage = (isReadMessage) => (messageData) => {
    let {
      notification: { title, body },
    } = messageData;

    let isRead = isReadMessage;

    messageNotification.push({ title, body, isRead });

    localStorage.setItem(
      keyMessageNotification,
      JSON.stringify(messageNotification)
    );
  };

  firebaseMessages.onBackgroundMessage(handleMessage(false))
  firebaseMessages.onMessage(handleMessage(true));
};
