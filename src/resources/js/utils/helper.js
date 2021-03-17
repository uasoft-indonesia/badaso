import store from "./../store/store";
import * as _ from "lodash";
import moment from "moment";

export default {
  generateDisplayName(fieldName) {
    let displayName = "";
    let words = fieldName.split("_");
    let displayNameWord = words.map((word) => {
      return word.charAt(0).toUpperCase() + word.slice(1);
    });
    displayName = displayNameWord.join(" ");
    return displayName;
  },
  
  generateDisplayNamePlural(fieldName) {
    let displayName = this.generateDisplayName(fieldName);
    var lastChar = displayName.substr(displayName.length - 1);
    if (lastChar) {
      return displayName;
    } else {
      return displayName + 's';
    }
  },

  generateSlug(string) {
    let str = string.replace(/^\s+|\s+$/g, "");

    // Make the string lowercase
    str = str.toLowerCase();

    // Remove accents, swap ñ for n, etc
    var from =
      "ÁÄÂÀÃÅČÇĆĎÉĚËÈÊẼĔȆÍÌÎÏŇÑÓÖÒÔÕØŘŔŠŤÚŮÜÙÛÝŸŽáäâàãåčçćďéěëèêẽĕȇíìîïňñóöòôõøðřŕšťúůüùûýÿžþÞĐđßÆa·/_,:;";
    var to =
      "AAAAAACCCDEEEEEEEEIIIINNOOOOOORRSTUUUUUYYZaaaaaacccdeeeeeeeeiiiinnooooooorrstuuuuuyyzbBDdBAa------";
    for (var i = 0, l = from.length; i < l; i++) {
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

  isArray(value){
    return Array.isArray(value)
  },

  isAllowed(permission) {
    let userPermissions = store.getters.getUser.permissions;
    let result = _.find(userPermissions, function(o) {
      return o.key == permission;
    });
    if (result) return true;
    else return false;
  },

  isAllowedToModifyGeneratedCRUD(action, dataType) {
    if (dataType.generatePermissions === true || dataType.generatePermissions === 1) {
      let userPermissions = store.getters.getUser.permissions;
      let result = _.find(userPermissions, function(o) {
        return o.key == action + '_' + dataType.name;
      });
      if (result) return true;
      else return false;
    } else {
      return true;
    }
  },

  formatDate(value) {
    let date = process.env.MIX_DATE_FORMAT ? process.env.MIX_DATE_FORMAT : 'MMMM Do YYYY';
    let time = process.env.MIX_TIME_FORMAT ? process.env.MIX_TIME_FORMAT : 'h:mm:ss a';
    return moment(value).format(`${date}, ${time}`);
  },

  isObjectEmpty(object) {
    return Object.keys(object).length === 0
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
  
  getMigrationTypeList() {
    return [
      {
        label: "Integer",
        value: "integer"
      },
      {
        label: "Varchar",
        value: "varchar"
      },
      {
        label: "Text",
        value: "text"
      },
      {
        label: "Date",
        value: "date"
      },
      {
        label: "Tiny Integer",
        value: "tinyint"
      },
      {
        label: "Small Integer",
        value: "smallint"
      },
      {
        label: "Medium Integer",
        value: "mediumint"
      },
      {
        label: "Big Integer",
        value: "bigint"
      },
      {
        label: "Decimal",
        value: "decimal"
      },
      {
        label: "Float",
        value: "float"
      },
      {
        label: "Double",
        value: "double"
      },
      {
        label: "Boolean",
        value: "boolean"
      },
      {
        label: "Datetime",
        value: "datetime"
      },
      {
        label: "Timestamp",
        value: "timestamp"
      },
      {
        label: "Time",
        value: "time"
      },
      {
        label: "Year",
        value: "year"
      },
      {
        label: "Char",
        value: "char"
      },
      {
        label: "Medium Text",
        value: "mediumtext"
      },
      {
        label: "Long Text",
        value: "longtext"
      },
      {
        label: "BLOB",
        value: "blob"
      },
      {
        label: "Enum",
        value: "enum"
      },
      {
        label: "Set",
        value: "set"
      },
      {
        label: "Geometry",
        value: "geometry"
      },
      {
        label: "Point",
        value: "point"
      },
      {
        label: "Multi Point",
        value: "multipoint"
      },
      {
        label: "Polygon",
        value: "polygon"
      },
      {
        label: "Multi Polygon",
        value: "multipolygon"
      },
      {
        label: "Linestring",
        value: "linestring"
      },
      {
        label: "Multi Linestring",
        value: "multilinestring"
      },
      {
        label: "JSON",
        value: "json"
      },
      {
        label: "Geometry Collection",
        value: "geometrycollection"
      },
    ]
  },

  getMigrationIndexList() {
    return [
      {
        label: "-",
        value: null,
        default: true
      },
      {
        label: "Unique",
        value: "unique",
        default: false
      },
      {
        label: "Index",
        value: "index",
        default: false
      },
      {
        label: "Spatial Index",
        value: "spatialIndex",
        default: false
      },
      {
        label: "Primary",
        value: "primary",
        default: false
      },
    ]
  },

  getMigrationDefaultList() {
    return [
      {
        label: "-",
        value: null,
        default: true
      },
      {
        label: "As Defined",
        value: "as_defined",
        default: false
      },
      {
        label: "Current Timestamp",
        value: "current_timestamp",
        default: false
      },
      {
        label: "Null",
        value: "null",
        default: false
      }
    ]
  },

  getMigrationAttributeList() {
    return [
      {
        label: "-",
        value: null
      },
      {
        label: "Unsigned",
        value: "unsigned",
      }
    ]
  },
};
