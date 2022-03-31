import Vue from "vue";
import Vuesax from "vuesax";
import VueI18n from "vue-i18n";
import { Datetime } from "vue-datetime";
import Vuelidate from "vuelidate";
import VueGtag from "vue-gtag";

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

Vue.config.productionTip = false;
Vue.config.devtools = true;

Vue.use(Vuesax);
Vue.use(VueI18n);
Vue.use(Datetime);
// eslint-disable-next-line vue/multi-word-component-names
Vue.component("datetime", Datetime);
Vue.use(Vuelidate);

// IDENTIFIED VARIABLE BROADCAST CHANNEL
const broadcastChannelName = "sw-badaso-messages";
let broadcastChannel = null;
try {
  broadcastChannel = new BroadcastChannel(broadcastChannelName);
} catch (error) {
  console.error("Broadcast Channel Error ", error);
}
// END IDENTIFIED VARIABLE BROADCAST CHANNEL

const pluginsEnv = process.env.MIX_BADASO_MODULES
  ? process.env.MIX_BADASO_MODULES
  : null;

// EXCLUDED ROUTES
let excluded = [];
excluded = excludedRouter;

// DYNAMIC IMPORT PLUGINS FOR COMPONENTS
try {
  if (pluginsEnv) {
    const plugins = process.env.MIX_BADASO_MODULES.split(",");
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

    Vue.component(str, componentConfig.default || componentConfig);
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

    Vue.component(str, componentConfig.default || componentConfig);
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
    Vue.prototype["$" + utilName] = requireUtils(fileName).default;
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
    Vue.prototype["$" + utilName] = requireCustomUtils(fileName).default;
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

    Vue.component(str, componentConfig.default || componentConfig);
  });
} catch (error) {
  console.info("Failed to load custom pages", error);
}

// DYNAMIC IMPORT PLUGINS FOR COMPONENTS
try {
  if (pluginsEnv) {
    const plugins = process.env.MIX_BADASO_MODULES.split(",");
    if (plugins && plugins.length > 0) {
      plugins.forEach((plugin) => {
        const fileName = require("../../../../" +
          plugin +
          "/src/resources/js/components/index.js").default;
        Object.values(fileName).forEach((value, index) => {
          Vue.component(value.name, value);
        });
      });
    }
  }
} catch (error) {
  console.info("Failed to load pages", error);
}

const i18n = new VueI18n({
  locale: "id",
  fallbackLocale: "en",
  messages: lang.i18n,
});

Vue.prototype.$readObjectStore = readObjectStore;
Vue.prototype.$setObjectStore = setObjectStore;
Vue.prototype.$api = api;
Vue.prototype.$handleError = handleError;
Vue.prototype.$resource = resource;
Vue.prototype.$constants = {
  MOBILE: "mobile",
  DESKTOP: "desktop",
};
Vue.prototype.$loadingConfig = {
  type: "sound",
  color: "#06bbd3",
};

const baseUrl = process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-dashboard";
Vue.prototype.$baseUrl = "/" + baseUrl;

Vue.prototype.$openLoader = function (payload) {
  try {
    this.$root.$children[0].openLoader(payload);
  } catch (error) {
    console.log("Open Loader", error);
  }
};

Vue.prototype.$closeLoader = function () {
  try {
    this.$root.$children[0].closeLoader();
  } catch (error) {
    console.log("Close Loader", error);
  }
};

Vue.prototype.$syncLoader = function (statusSyncLoader) {
  try {
    this.$root.$children[0].syncLoader(statusSyncLoader);
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

Vue.prototype.$messaging = {};
Vue.prototype.$messagingToken = {};
Vue.prototype.$statusActiveFeatureFirebase = statusActiveFeatureFirebase;

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

  Vue.prototype.$messaging = firebase.messaging();
  Vue.prototype.$messagingToken = firebase
    .messaging()
    .getToken({ vapidKey: process.env.MIX_FIREBASE_WEB_PUSH_CERTIFICATES });
}
// END ADD FIREBASE

// IDENTIFIED BROADCAST CHANNEL
Vue.prototype.$broadcastChannelName = broadcastChannelName;
Vue.prototype.$broadcastChannel = broadcastChannel;

// START G-TAG

Vue.use(
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

const app = new Vue({
  store,
  router,
  i18n,
  render: (h) => h(App),
}).$mount("#app");

// HANDLE FIREBASE MESSAGE
if (statusActiveFeatureFirebase) notificationMessageReceiveHandle(app);

// HANDLE BROADCAST MESSAGE FROM SERVICE WORKER
broadcastMessageHandle(app);

// HANDLE OFFLINE MODE
checkConnection(app);
