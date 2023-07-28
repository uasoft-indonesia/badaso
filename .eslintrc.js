module.exports = {
  env: {
    es6: true,
    node: true,
    browser: true,
  },
  extends: [
    "plugin:vue/essential",
    "standard",
    "eslint:recommended",
    "plugin:prettier/recommended",
  ],
  parserOptions: {
    ecmaVersion: 2020,
    sourceType: "module",
    allowImportExportEverywhere: true,
    ecmaFeatures: {
      jsx: true,
    },
  },
  plugins: ["vue", "flowtype", "prettier"],
  globals: {
    __WEEX__: true,
    WXEnvironment: true,
  },
  rules: {
    "no-console": process.env.NODE_ENV !== "production" ? 0 : 2,
    "no-useless-escape": 0,
    "no-empty": 0,
    "vue/no-mutating-props": "off",
    "no-prototype-builtins": "off",
    eqeqeq: "off",
  },
};
