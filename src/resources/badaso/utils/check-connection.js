import {
  getAllKeysObjectStore,
  readObjectStore,
  removeObjectStore,
} from "./indexed-db";
import http from "../api/resource";

export const checkConnection = (app) => {
  let isOnlineConnectionStatus = navigator.onLine;
  let onlineHistory = isOnlineConnectionStatus;

  let store = app.$store;
  setInterval(async () => {
    isOnlineConnectionStatus = navigator.onLine;
    store.commit("badaso/SET_GLOBAL_STATE", {
      key: "isOnline",
      value: isOnlineConnectionStatus,
    });

    if (isOnlineConnectionStatus) {
      if (isOnlineConnectionStatus != onlineHistory) app.$syncLoader(true);

      try {
        let keys = await getAllKeysObjectStore();

        for (let index in keys) {
          let keyStore = keys[index];

          let store = await readObjectStore(keyStore);
          let data = store.result.data;
          for (let indexItemData in data) {
            let itemData = data[indexItemData];
            let {
              requestData,
              requestMethod,
              requestUrl,
              requestHeaders,
            } = itemData;

            try {
              await http({
                method: requestMethod,
                data: requestData,
                url: requestUrl,
                headers: requestHeaders,
              });

              removeObjectStore(keyStore);
            } catch (error) {
              console.error("HTTP REQUEST ERROR", error);
            }
          }
        }
      } catch (error) {
        console.error(error);
      }

      if (isOnlineConnectionStatus != onlineHistory) app.$syncLoader(false);
      onlineHistory = isOnlineConnectionStatus;
    } else {
      onlineHistory = false;
    }
  }, 1000);
};
