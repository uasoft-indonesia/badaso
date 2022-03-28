export const objectName = "offline-store-request";

export const indexedDatabase = () => {
  if (!indexedDB)
    return console.error(
      "Your browser doesn't support a stable version of IndexedDB."
    );

  async function getDB() {
    const requestdb = indexedDB.open("badaso-indexed-db", 1);

    return await new Promise((resolve, reject) => {
      requestdb.onerror = () => {
        reject(requestdb.error);
      };
      requestdb.onsuccess = (event) => {
        resolve(event.target.result);
      };
      requestdb.onupgradeneeded = (event) => {
        const db = event.target.result;
        db.createObjectStore(objectName);
      };
    });
  }

  return getDB();
};

export const readObjectStore = async (keyStore) => {
  const db = await indexedDatabase();
  const dbTransaction = db.transaction([objectName], "readonly");
  let dbObjectStore = dbTransaction.objectStore(objectName);
  dbObjectStore = dbObjectStore.get(keyStore);

  return new Promise((resolve, reject) => {
    dbObjectStore.onsuccess = (event) => resolve(event.target);
    dbObjectStore.onerror = (event) => {
      if (dbObjectStore.error) {
        reject(dbObjectStore.error);
      }
    };
  });
};

export const getAllKeysObjectStore = async () => {
  const db = await indexedDatabase();
  const dbTransaction = db.transaction([objectName], "readonly");
  const dbObjectStore = dbTransaction.objectStore(objectName);

  const getAllKeysRequest = dbObjectStore.getAllKeys();

  return new Promise((resolve, reject) => {
    getAllKeysRequest.onsuccess = () => {
      resolve(getAllKeysRequest.result);
    };
  });
};

export const setObjectStore = async (key, value) => {
  const db = await indexedDatabase();
  const dbTransaction = db.transaction([objectName], "readwrite");
  const dbObjectStore = dbTransaction.objectStore(objectName);
  dbObjectStore.put(value, key);
};

export const removeObjectStore = async (keyStore) => {
  const db = await indexedDatabase();
  const dbTransaction = db.transaction([objectName], "readwrite");
  const dbObjectStore = dbTransaction.objectStore(objectName);

  const deleteObjectStore = dbObjectStore.delete(keyStore);

  return new Promise((resolve, reject) => {
    deleteObjectStore.onsuccess = resolve();

    deleteObjectStore.onerror = reject;
  });
};
