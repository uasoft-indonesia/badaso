import {
  getAllKeysObjectStore,
  readObjectStore,
  removeObjectStore,
} from "./indexed-db";
import http from "../api/resource";

export const checkConnection = (app) => {
  let isOnlineConnectionStatus = navigator.onLine;
  let onlineHistory = isOnlineConnectionStatus;

  const store = app.$store;
  setInterval(async () => {
    isOnlineConnectionStatus = navigator.onLine;
    store.commit("badaso/SET_GLOBAL_STATE", {
      key: "isOnline",
      value: isOnlineConnectionStatus,
    });

    if (isOnlineConnectionStatus) {
      if (isOnlineConnectionStatus != onlineHistory) app.$syncLoader(true);

      try {
        const keys = await getAllKeysObjectStore();

        for (const index in keys) {
          const keyStore = keys[index];

          const store = await readObjectStore(keyStore);
          const data = store.result.data;
          for (const indexItemData in data) {
            const itemData = data[indexItemData];
            const { requestData, requestMethod, requestUrl, requestHeaders } =
              itemData;

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
