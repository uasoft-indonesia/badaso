export default (object) => {
  let qs = "?";
  if (object !== null) {
    const keys = Object.keys(object);
    for (let i = 0; i < keys.length; i++) {
      if (i > 0) {
        qs = qs + "&";
      }
      qs = qs + keys[i] + "=" + object[keys[i]];
    }
  }
  return qs;
};
