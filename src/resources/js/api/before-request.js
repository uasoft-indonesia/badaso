import { indexedDatabase, objectName } from "../utils/indexed-db";

export default async (config) => {
  if (config.method.toLowerCase() == "get") return;
  if (config.data) {
    let { origin, pathname, host } = window.location;
    let { data, headers, url, method } = config;

    let keyStore = pathname;

    let value = {
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
        let db = await indexedDatabase();

        const readObject = () => {
          let dbTransaction = db.transaction([objectName], "readonly");
          let dbObjectStore = dbTransaction.objectStore(objectName);
          return dbObjectStore.get(keyStore);
        };

        const setObject = (key, value) => {
          let dbTransaction = db.transaction([objectName], "readwrite");
          let dbObjectStore = dbTransaction.objectStore(objectName);
          dbObjectStore.put(value, key);
        };

        let dataObject = readObject();

        dataObject.onsuccess = (event) => {
          let result = event.target.result;
          if (result) {
            let indexAdd = result.data.length;
            if (
              config.method.toLowerCase() == "post" ||
              config.method.toLowerCase() == "delete"
            ) {
              result.data[indexAdd] = value;
              setObject(keyStore, result);
            } else {
              let { requestUrl } = result.data[indexAdd - 1];
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
