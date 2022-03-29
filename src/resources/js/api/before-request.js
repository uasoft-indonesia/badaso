import { indexedDatabase, objectName } from "../utils/indexed-db";

export default async (config) => {
  if (config.method.toLowerCase() == "get") return;
  if (config.data) {
    const { origin, pathname, host } = window.location;
    const { data, headers, url, method } = config;

    const keyStore = pathname;

    const value = {
      origin,
      pathname,
      host,
      requestData: data,
      requestHeaders: headers,
      requestUrl: url,
      requestMethod: method,
    };

    if (JSON.stringify(config.data) != "{}") {
      if (!navigator.onLine) {
        const db = await indexedDatabase();

        const readObject = () => {
          const dbTransaction = db.transaction([objectName], "readonly");
          const dbObjectStore = dbTransaction.objectStore(objectName);
          return dbObjectStore.get(keyStore);
        };

        const setObject = (key, value) => {
          const dbTransaction = db.transaction([objectName], "readwrite");
          const dbObjectStore = dbTransaction.objectStore(objectName);
          dbObjectStore.put(value, key);
        };

        const dataObject = readObject();

        dataObject.onsuccess = (event) => {
          const result = event.target.result;
          if (result) {
            const indexAdd = result.data.length;
            if (
              config.method.toLowerCase() == "post" ||
              config.method.toLowerCase() == "delete"
            ) {
              result.data[indexAdd] = value;
              setObject(keyStore, result);
            } else {
              const { requestUrl } = result.data[indexAdd - 1];
              if (requestUrl != value.requestUrl) {
                setObject(keyStore, {
                  data: [value],
                });
              } else {
                result.data[indexAdd] = value;
                setObject(keyStore, result);
              }
            }
          } else {
            setObject(keyStore, {
              data: [value],
            });
          }
        };
      }
    }
  }
};
