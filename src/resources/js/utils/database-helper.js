export default {
  getMigrationIndexList() {
    return [
      {
        label: "Unique",
        value: "unique",
        default: false,
      },
      {
        label: "Index",
        value: "index",
        default: false,
      },
      {
        label: "Primary",
        value: "primary",
        default: false,
      },
    ];
  },
};
