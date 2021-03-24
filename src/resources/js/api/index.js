let exported = {}
const modules = require.context("./modules", false, /\.js$/); // 

modules.keys().forEach((fileName) => {
  let property = fileName.replace('./', '').replace('.js', '');
  exported[property] = modules(fileName).default
})

export default exported;
