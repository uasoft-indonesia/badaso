import { createStore } from "vuex";
import _ from "lodash";

const exported = {};

// DYNAMIC IMPORT BADASO STORES
try {
  const modules = require.context("./modules", false, /\.js$/);
  modules.keys().forEach((fileName) => {
    const moduleName = fileName
      .replace("./", "")
      .replace(".js", "")
      .replace(/([a-z])([A-Z])/g, "$1-$2")
      .replace(/[\s_]+/g, "-")
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((word, index) =>
        index > 0 ? word.charAt(0).toUpperCase() + word.slice(1) : word
      )
      .join("");

    // Ensure namespaced property is true for better modularization
    exported[moduleName] = {
      namespaced: true,
      ...modules(fileName).default,
    };
  });
} catch (error) {
  console.info("Failed to load Badaso stores", error);
}

// DYNAMIC IMPORT CUSTOM STORES
try {
  const customModules = require.context(
    "../../../../../../../resources/js/badaso/stores",
    false,
    /\.js$/
  );
  customModules.keys().forEach((fileName) => {
    const moduleName = fileName
      .replace("./", "")
      .replace(".js", "")
      .replace(/([a-z])([A-Z])/g, "$1-$2")
      .replace(/[\s_]+/g, "-")
      .replace(/^\.\/_/, "")
      .replace(/\.\w+$/, "")
      .split("-")
      .map((word, index) =>
        index > 0 ? word.charAt(0).toUpperCase() + word.slice(1) : word
      )
      .join("");

    // Ensure namespaced property is true for better modularization
    exported[moduleName] = {
      namespaced: true,
      ...customModules(fileName).default,
    };
  });
} catch (error) {
  console.info("Failed to load custom stores", error);
}

// DYNAMIC IMPORT BADASO PLUGINS STORES
try {
  const pluginsEnv = process.env.MIX_BADASO_PLUGINS
    ? process.env.MIX_BADASO_PLUGINS.split(",")
    : [];

  if (pluginsEnv.length > 0) {
    pluginsEnv.forEach((plugin) => {
      const modules = require("../../../../../" +
        plugin +
        "/src/resources/js/store/badaso.js").default;

      // Merge the plugin modules with existing Badaso modules
      exported.badaso = _.merge(
        exported.badaso || { namespaced: true },
        modules
      );
    });
  }
} catch (error) {
  console.info("Failed to load custom stores", error);
}

const store = createStore({
  modules: exported,
});

export default store;
