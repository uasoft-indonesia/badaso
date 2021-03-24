export default {
  getMigrationTypeList() {
    return [
      {
        title: "Frequently used",
        group: [
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
        ],
      },
      {
        title: "Numeric",
        group: [
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
        ]
      },
      {
        title: "Date and Time",
        group: [
          {
            label: "Datetime",
            value: "datetime"
          },
          {
            label: "Time",
            value: "time"
          },
          {
            label: "Year",
            value: "year"
          },
        ]
      },
      {
        title: "String",
        group: [
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
        ]
      },
      {
        title: "Spatial",
        group: [
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
            label: "Geometry Collection",
            value: "geometrycollection"
          },
        ]
      },
      {
        title: "JSON",
        group: [
          {
            label: "JSON",
            value: "json"
          },
        ]
      }
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
}