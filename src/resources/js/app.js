import { createApp } from "vue";
import Vuesax from "vuesax3";
import { createI18n } from "vue-i18n";
import Datetime from "vue-datetime";
import Vuelidate from "vuelidate";
import VueGtag from "vue-gtag-next";

import api from "./api/index";
import handleError from "./api/handle-error";
import resource from "./api/resource";
import router from "./router/router";
import store from "./store/store";
import lang from "./lang/";
import excludedRouter from "./router/excludeRouter";

import App from "./apps/App.vue";
import firebase from "firebase/app";
import "firebase/firebase-messaging";
import { notificationMessageReceiveHandle } from "./utils/firebase";
import { broadcastMessageHandle } from "./utils/broadcast-messages";
import { checkConnection } from "./utils/check-connection";
import { readObjectStore, setObjectStore } from "./utils/indexed-db";

const app = createApp(App);

app.use(Vuesax);

app.use(Vuelidate);
app.use(Datetime);

app.config.productionTip = false;
app.config.devtools = true;

// IDENTIFIED VARIABLE BROADCAST CHANNEL
const broadcastChannelName = "sw-badaso-messages";
let broadcastChannel = null;
try {
  broadcastChannel = new BroadcastChannel(broadcastChannelName);
} catch (error) {
  console.error("Broadcast Channel Error ", error);
}
// END IDENTIFIED VARIABLE BROADCAST CHANNEL

const pluginsEnv = process.env.MIX_BADASO_PLUGINS
  ? process.env.MIX_BADASO_PLUGINS
  : null;

// EXCLUDED ROUTES
let excluded = [];
excluded = excludedRouter;

// DYNAMIC IMPORT PLUGINS FOR COMPONENTS
try {
  if (pluginsEnv) {
    const plugins = process.env.MIX_BADASO_PLUGINS.split(",");
    if (plugins && plugins.length > 0) {
      plugins.forEach((plugin) => {
        const router = require("../../../../" +
          plugin +
          "/src/resources/js/router/excludeRouter.js").default;
        excluded.push(...router);
      });
    }
  }
} catch (error) {
  console.info("Failed to load pages", error);
}

// DYNAMIC IMPORT BADASO COMPONENT
try {
  const requireComponent = require.context(
    "./components",
    false,
    /[\w-]+\.vue$/
  );
  requireComponent.keys().forEach((fileName) => {
    const componentConfig = requireComponent(fileName);
    const componentName = fileName
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((kebab) => kebab.charAt(0).toUpperCase() + kebab.slice(1))
      .join("");

    const str = componentName
      .replace(/([a-z])([A-Z])/g, "$1-$2") // get all lowercase letters that are near to uppercase ones
      .replace(/[\s_]+/g, "-") // replace all spaces and low dash
      .toLowerCase() // convert to lower case
      .replace("./", "");

    app.component(str, componentConfig.default || componentConfig);
  });
} catch (error) {
  console.info("Failed to load badaso components", error);
}

// DYNAMIC IMPORT CUSTOM COMPONENT
try {
  const requireCustomComponent = require.context(
    "../../../../../../resources/js/badaso/components",
    false,
    /[\w-]+\.vue$/
  );
  requireCustomComponent.keys().forEach((fileName) => {
    const componentConfig = requireCustomComponent(fileName);
    const componentName = fileName
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((kebab) => kebab.charAt(0).toUpperCase() + kebab.slice(1))
      .join("");

    const str = componentName
      .replace(/([a-z])([A-Z])/g, "$1-$2") // get all lowercase letters that are near to uppercase ones
      .replace(/[\s_]+/g, "-") // replace all spaces and low dash
      .toLowerCase() // convert to lower case
      .replace("./", "");

    app.component(str, componentConfig.default || componentConfig);
  });
} catch (error) {
  console.info("Failed to load custom components", error);
}

// DYNAMIC IMPORT BADASO UTILS
try {
  const requireUtils = require.context("./utils", false, /\.js$/);
  requireUtils.keys().forEach((fileName) => {
    const utilName = fileName
      .replace("./", "")
      .replace(".js", "")
      .replace(/([a-z])([A-Z])/g, "$1-$2") // get all lowercase letters that are near to uppercase ones
      .replace(/[\s_]+/g, "-") // replace all spaces and low dash
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((word, index) => {
        if (index > 0) {
          return word.charAt(0).toUpperCase() + word.slice(1);
        } else {
          return word;
        }
      })
      .join("");
    app.config.globalProperties["$" + utilName] =
      requireUtils(fileName).default;
  });
} catch (error) {
  console.info("Failed to load badaso utils", error);
}

// DYNAMIC IMPORT CUSTOM UTILS
try {
  const requireCustomUtils = require.context(
    "../../../../../../resources/js/badaso/utils",
    false,
    /\.js$/
  );
  requireCustomUtils.keys().forEach((fileName) => {
    const utilName = fileName
      .replace("./", "")
      .replace(".js", "")
      .replace(/([a-z])([A-Z])/g, "$1-$2") // get all lowercase letters that are near to uppercase ones
      .replace(/[\s_]+/g, "-") // replace all spaces and low dash
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((word, index) => {
        if (index > 0) {
          return word.charAt(0).toUpperCase() + word.slice(1);
        } else {
          return word;
        }
      })
      .join("");
    app.config.globalProperties["$" + utilName] =
      requireCustomUtils(fileName).default;
  });
} catch (error) {
  console.info("Failed to load custom utils", error);
}

// DYNAMIC IMPORT CUSTOM PAGES
try {
  const requireCustomPages = require.context(
    "../../../../../../resources/js/badaso/pages",
    true,
    /[\w-]+\.vue$/
  );
  requireCustomPages.keys().forEach((fileName) => {
    const componentConfig = requireCustomPages(fileName);
    const componentName = fileName
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((kebab) => kebab.charAt(0).toUpperCase() + kebab.slice(1))
      .join("");

    const str = componentName
      .replace(/([a-z])([A-Z])/g, "$1-$2") // get all lowercase letters that are near to uppercase ones
      .replace(/[\s_]+/g, "-") // replace all spaces and low dash
      .toLowerCase() // convert to lower case
      .replace("./", "")
      .replace("/", "-");

    app.component(str, componentConfig.default || componentConfig);
  });
} catch (error) {
  console.info("Failed to load custom pages", error);
}

// DYNAMIC IMPORT PLUGINS FOR COMPONENTS
try {
  if (pluginsEnv) {
    const plugins = process.env.MIX_BADASO_PLUGINS.split(",");
    if (plugins && plugins.length > 0) {
      plugins.forEach((plugin) => {
        const fileName = require("../../../../" +
          plugin +
          "/src/resources/js/components/index.js").default;
        Object.values(fileName).forEach((value, index) => {
          app.component(value.name, value);
        });
      });
    }
  }
} catch (error) {
  console.info("Failed to load pages", error);
}

// Use VueI18n
const i18n = createI18n({
  locale: "id",
  fallbackLocale: "en",
  messages: lang.i18n,
});

app.config.globalProperties.$readObjectStore = readObjectStore;
app.config.globalProperties.$setObjectStore = setObjectStore;
app.config.globalProperties.$api = api;
app.config.globalProperties.$handleError = handleError;
app.config.globalProperties.$resource = resource;
app.config.globalProperties.$constants = {
  MOBILE: "mobile",
  DESKTOP: "desktop",
};
app.config.globalProperties.$loadingConfig = {
  type: "sound",
  color: "#06bbd3",
};

const baseUrl = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-dashboard";
app.config.globalProperties.$baseUrl = "/" + baseUrl;


// app.config.globalProperties.$openLoader = function (payload) {
//   try {
//     const instance = getCurrentInstance();
//     instance.openLoader(payload);
//   } catch (error) {
//     console.log("Open Loader", error);
//   }
// };

// app.config.globalProperties.$closeLoader = function () {
//   try {
//     const instance = getCurrentInstance();
//     instance.closeLoader();
//   } catch (error) {
//     console.log("Close Loader", error);
//   }
// };

// app.config.globalProperties.$syncLoader = function (statusSyncLoader) {
//   try {
//     const instance = getCurrentInstance();
//     instance.syncLoader(statusSyncLoader);
//   } catch (error) {
//     console.log("Sync Loader", error);
//   }
// };

app.config.globalProperties.$openLoader = function (payload) {
  try {
    this.$root.openLoader(payload);
  } catch (error) {
    console.log("Open Loader", error);
  }
};

app.config.globalProperties.$closeLoader = function () {
  try {
    this.$root.closeLoader();
  } catch (error) {
    console.log("Close Loader", error);
  }
};

app.config.globalProperties.$syncLoader = function (statusSyncLoader) {
  try {
    this.$root.syncLoader(statusSyncLoader);
  } catch (error) {
    console.log("Sync Loader", error);
  }
};

// ADD FIREBASE MESSAGE
const firebaseConfig = {
  apiKey: process.env.MIX_FIREBASE_API_KEY,
  authDomain: process.env.MIX_FIREBASE_AUTH_DOMAIN,
  projectId: process.env.MIX_FIREBASE_PROJECT_ID,
  storageBucket: process.env.MIX_FIREBASE_STORAGE_BUCKET,
  messagingSenderId: process.env.MIX_FIREBASE_MESSAGE_SEENDER,
  appId: process.env.MIX_FIREBASE_APP_ID,
  measurementId: process.env.MIX_FIREBASE_MEASUREMENT_ID,
};

let statusActiveFeatureFirebase = true;
for (const key in firebaseConfig)
  statusActiveFeatureFirebase =
    statusActiveFeatureFirebase &&
    firebaseConfig[key] != undefined &&
    firebaseConfig[key] != "";

app.config.globalProperties.$messaging = {};
app.config.globalProperties.$messagingToken = {};
app.config.globalProperties.$statusActiveFeatureFirebase =
  statusActiveFeatureFirebase;

if ("serviceWorker" in navigator) {
  window.addEventListener("load", () => {
    // register worker
    navigator.serviceWorker
      .register("/firebase-messaging-sw.js")
      .then((register) => {})
      .catch((error) =>
        console.log("Service Worker Firebase Register Failed : ", error)
      );
  });
}

if (statusActiveFeatureFirebase) {
  if (!firebase.apps.length) {
    firebase.initializeApp(firebaseConfig);
  } else {
    firebase.app();
  }

  app.config.globalProperties.$messaging = firebase.messaging();
  app.config.globalProperties.$messagingToken = firebase
    .messaging()
    .getToken({ vapidKey: process.env.MIX_FIREBASE_WEB_PUSH_CERTIFICATES });
}
// END ADD FIREBASE

// IDENTIFIED BROADCAST CHANNEL
app.config.globalProperties.$broadcastChannelName = broadcastChannelName;
app.config.globalProperties.$broadcastChannel = broadcastChannel;

// START G-TAG
app.use(
  VueGtag,
  {
    pageTrackerExcludedRoutes: excluded,
    config: {
      id: process.env.MIX_ANALYTICS_TRACKING_ID
        ? process.env.MIX_ANALYTICS_TRACKING_ID
        : null,
      params: {
        send_page_view: true,
      },
    },
  },
  router
);
// END G-TAG
// app.use(router).use(store).mount("#app");
app.use(router);
app.use(store);
app.use(i18n);
app.mount("#app");

// HANDLE FIREBASE MESSAGE
if (statusActiveFeatureFirebase) notificationMessageReceiveHandle(app);

// HANDLE BROADCAST MESSAGE FROM SERVICE WORKER
broadcastMessageHandle(app);

// HANDLE OFFLINE MODE
checkConnection(app);
