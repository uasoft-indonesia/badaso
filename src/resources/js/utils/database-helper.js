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
      {
        label: "Foreign",
        value: "foreign",
        default: false,
      },
    ];
  },
  getForeignConstraint() {
    return [
      {
        label: "CASCADE",
        value: "cascade",
      },
      {
        label: "SET NULL",
        value: "set null",
      },
      {
        label: "NO ACTION",
        value: "no action",
      },
      {
        label: "RESTRICT",
        value: "restrict",
      },
    ];
  },
};
