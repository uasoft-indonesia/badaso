import Vue from "vue";
import Vuex from "vuex";

let exported = {};
const modules = require.context("./modules", false, /\.js$/); //

modules.keys().forEach((fileName) => {
  let property = fileName
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
  exported[property] = modules(fileName).default;
});
Vue.use(Vuex);
/* eslint-disable */
export default new Vuex.Store({
  modules: exported
});
