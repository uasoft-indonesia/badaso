export default {
  snake(obj) {
    if (typeof obj != "object") return obj;

    for (var oldName in obj) {
      // Camel to underscore
      let newName = oldName.replace(/([A-Z])/g, function($1) {
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
};
