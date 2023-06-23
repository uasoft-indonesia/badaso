export default {
  snake(obj) {
    if (typeof obj == "string") {
      return obj.replace(/([A-Z])/g, function ($1) {
        return "_" + $1.toLowerCase();
      });
    }
    if (typeof obj != "object") return obj;

    for (const oldName in obj) {
      // Camel to underscore
      const newName = oldName.replace(/([A-Z])/g, function ($1) {
        return "_" + $1.toLowerCase();
      });

      // Only process if names are different
      if (newName != oldName) {
        // Check for the old property name to avoid a ReferenceError in strict mode.
        if (obj.hasOwnProperty(oldName)) {
          obj[newName] = obj[oldName];
          delete obj[oldName];
        }
      }

      // Recursion
      if (typeof obj[newName] == "object") {
        obj[newName] = this.snake(obj[newName]);
      }

      // Array
      if (Array.isArray(obj[newName])) {
        for (let index = 0; index < obj[newName].length; index++) {
          if (typeof obj[newName] == "object") {
            obj[newName] = this.snake(obj[newName]);
          }
        }
      }
    }
    return obj;
  },
  stringSnakeToCamel(str) {
    const strings = str.split("_");
    const newStrings = strings.map((string, index) => {
      if (index > 0) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      } else {
        return string;
      }
    });
    const string = newStrings.join("");
    return string;
  },
  kebab(string) {
    let kebab = string
      .replace(/([a-z0-9]|(?=[A-Z]))([A-Z])/g, "$1-$2")
      .toLowerCase();
    if (kebab.charAt(0) === "-") {
      kebab = kebab.substring(1);
    }
    return kebab;
  },
};
