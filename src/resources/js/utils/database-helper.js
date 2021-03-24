export default {
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
}