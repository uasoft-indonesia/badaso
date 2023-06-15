import store from "./../store/store";
import * as _ from "lodash";
import moment from "moment";
import { v4 as uuidv4 } from "uuid";

export default {
  generateDisplayName(fieldName) {
    let displayName = "";
    const words = fieldName.split("_");
    const displayNameWord = words.map((word) => {
      return word.charAt(0).toUpperCase() + word.slice(1);
    });
    displayName = displayNameWord.join(" ");
    return displayName;
  },

  generateClassName(fieldName) {
    return this.generateDisplayName(fieldName).replace(" ", "");
  },

  generateDisplayNamePlural(fieldName) {
    const displayName = this.generateDisplayName(fieldName);
    const lastChar = displayName.substr(displayName.length - 1);
    if (lastChar) {
      return displayName;
    } else {
      return displayName + "s";
    }
  },

  generateSlug(string) {
    let str = string.replace(/^\s+|\s+$/g, "");

    // Make the string lowercase
    str = str.toLowerCase();

    // Remove accents, swap ñ for n, etc
    const from =
      "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
    const to =
      "AAAAAACCCDEEEEEEEEIIIINNOOOOOORRSTUUUUUYYZaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";
    for (let i = 0, l = from.length; i < l; i++) {
      str = str.replace(new RegExp(from.charAt(i), "g"), to.charAt(i));
    }

    // Remove invalid chars
    str = str
      .replace(/[^a-z0-9 -]/g, "")
      // Collapse whitespace and replace by -
      .replace(/\s+/g, "-")
      // Collapse dashes
      .replace(/-+/g, "-");

    return str;
  },

  isArray(value) {
    return Array.isArray(value);
  },

  isAllowed(permission) {
    const userPermissions = store.getters["badaso/getUser"].permissions;
    const result = _.find(userPermissions, function (o) {
      return o.key == permission;
    });
    if (result) return true;
    else return false;
  },

  isAllowedToModifyGeneratedCRUD(action, dataType) {
    if (
      dataType.generatePermissions === true ||
      dataType.generatePermissions === 1
    ) {
      const userPermissions = store.getters["badaso/getUser"].permissions;
      const result = _.find(userPermissions, function (o) {
        return o.key == action + "_" + dataType.name;
      });
      if (result) return true;
      else return false;
    } else {
      return true;
    }
  },

  formatDate(value) {
    const date = import.meta.env.VITE_DATE_FORMAT
      ? import.meta.env.VITE_DATE_FORMAT
      : "MMMM Do YYYY";
    const time = import.meta.env.VITE_TIME_FORMAT
      ? import.meta.env.VITE_TIME_FORMAT
      : "h:mm:ss a";
    return moment(value).format(`${date}, ${time}`);
  },

  isObjectEmpty(object) {
    return Object.keys(object).length === 0;
  },

  isValidHttpUrl(string) {
    let url;

    try {
      url = new URL(string);
    } catch (_) {
      return false;
    }

    return url.protocol === "http:" || url.protocol === "https:";
  },
  objectFlip(obj) {
    const ret = {};
    Object.keys(obj).forEach((key) => {
      ret[obj[key]] = key;
    });
    return ret;
  },
  uuid() {
    return uuidv4();
  },
  hexToVsPrimary(colorHex) {
    const str = colorHex.substring(1);
    const aRgbHex = str.match(/.{1,2}/g);
    const aRgb = [
      parseInt(aRgbHex[0], 16),
      parseInt(aRgbHex[1], 16),
      parseInt(aRgbHex[2], 16),
    ];
    return aRgb.join(",");
  },
  rgbToVsPrimary(colorRgb) {
    const aRgb = colorRgb
      .substring(5, colorRgb.length - 1)
      .split(",")
      .filter((rgb, index) => index != 3)
      .map((rgb) => rgb.trim())
      .join(",");
    return aRgb;
  },
};
