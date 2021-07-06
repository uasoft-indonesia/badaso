<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_database') && isCanEdit">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("database.edit.title") }}</h3>
          </div>
          <vs-row vs-align="center">
            <vs-col vs-lg="6" vs-align="center">
              <badaso-text
                v-model="databaseData.table.modifiedName"
                size="12"
                :label="$t('database.edit.field.table')"
                :placeholder="$t('database.edit.field.table')"
                required
              >
              </badaso-text>
            </vs-col>
            <vs-col vs-lg="12">
              <div v-if="$v.databaseData.table.modifiedName.$dirty">
                <i18n path="vuelidate.required" class="is-error" v-if="!$v.databaseData.table.modifiedName.required" >
                  {{ $t("database.edit.row.field.tableName") }} <br />
                </i18n>

                <i18n path="vuelidate.alphaNumAndUnderscoreValidator" class="is-error" v-if="!$v.databaseData.table.modifiedName .alphaNumAndUnderscoreValidator " >
                  {{ $t("database.edit.row.field.tableName") }} <br />
                </i18n>

                <i18n path="vuelidate.maxLength" class="is-error" v-if="!$v.databaseData.table.modifiedName.maxLength" >
                  <template v-slot:field>
                    {{ $t("database.edit.row.field.tableName") }}
                  </template>
                  <template v-slot:length>
                    {{
                      $v.databaseData.table.modifiedName.$params.maxLength.max
                    }}
                  </template>
                </i18n>
              </div>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("database.edit.row.title") }}</h3>
          </div>
          <badaso-alert-block>
            <template slot="title">{{ $t('database.edit.warning.title') }}</template>
            <template slot="desc">{{ $t('database.edit.warning.content') }}</template>
          </badaso-alert-block>
          <vs-row vs-justify="center" vs-align="center">
            <vs-col col-lg="12">
              <vs-table :data="databaseData.fields.modifiedFields">
                <template slot="thead">
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldName") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldType") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldLength") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldNull") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldAttribute") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldIncrement") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldIndex") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap">
                    {{ $t("database.add.row.field.fieldDefault") }}
                  </vs-th>
                  <vs-th class="badaso-table__no-wrap"></vs-th>
                </template>
                <template slot-scope="{ data }">
                  <template v-for="(tr, indextr) in data">
                    <vs-tr :key="indextr">
                      <vs-td :data="tr.fieldName">
                        <vs-input
                          type="text"
                          required
                          :value="tr.fieldName"
                          class="inputx"
                          @change="alterFieldProperty(tr, $event, 'RENAME', 'fieldName', indextr)"
                          :disabled="tr.undeletable"
                        />
                      </vs-td>

                      <vs-td :data="tr.fieldType">
                        <vs-select
                          class="database-management__field-type"
                          :value="tr.fieldType"
                          @input="alterFieldProperty(tr, $event, 'UPDATE_TYPE', 'fieldType', indextr)"
                          :disabled="tr.undeletable"
                        >
                          <div
                            :key="index"
                            v-for="(item, index) in fieldTypeList"
                          >
                            <vs-select-group
                              :title="item.title"
                              v-if="item.group"
                            >
                              <vs-select-item
                                :key="index"
                                :value="item.value"
                                :text="item.label"
                                v-for="(item, index) in item.group"
                              />
                            </vs-select-group>
                          </div>
                        </vs-select>
                      </vs-td>

                      <vs-td :data="tr.fieldLength">
                        <vs-input
                          type="text"
                          required
                          :value="tr.fieldLength"
                          :disabled="tr.undeletable"
                          @change="alterFieldProperty(tr, $event, 'UPDATE_LENGTH', 'fieldLength', indextr)"
                        />
                      </vs-td>

                      <vs-td :data="tr.fieldNull">
                        <vs-checkbox :value="tr.fieldNull" :disabled="tr.undeletable" @change="alterFieldProperty(tr, $event, 'UPDATE_NULL', 'fieldNull', indextr)"></vs-checkbox>
                      </vs-td>

                      <vs-td :data="tr.fieldAttribute">
                        <vs-checkbox :value="tr.fieldAttribute" :disabled="tr.undeletable" @change="alterFieldProperty(tr, $event, 'UPDATE_ATTRIBUTE', 'fieldAttribute', indextr)"></vs-checkbox>
                      </vs-td>

                      <vs-td :data="tr.fieldIncrement">
                        <vs-checkbox :value="tr.fieldIncrement" :disabled="tr.undeletable" @change="alterFieldProperty(tr, $event, 'UPDATE_INCREMENT', 'fieldIncrement', indextr)"></vs-checkbox>
                      </vs-td>

                      <vs-td :data="tr.fieldIndex">
                        <vs-select
                          class="database-management__field-index"
                          :value="tr.fieldIndex"
                          @input="alterFieldProperty(tr, $event, 'UPDATE_INDEX', 'fieldIndex', indextr)"
                          :disabled="tr.undeletable"
                        >
                          <vs-select-item
                            text="-"
                          />
                          <vs-select-item
                            :key="index"
                            :value="item.value"
                            :text="item.label"
                            v-for="(item, index) in fieldIndexList"
                          />
                        </vs-select>
                      </vs-td>

                      <vs-td :data="tr.fieldDefault">
                        <vs-input
                          type="text"
                          :value="tr.fieldDefault"
                          @change="alterFieldProperty(tr, $event, 'UPDATE_DEFAULT', 'fieldDefault', indextr)"
                          :disabled="tr.undeletable"
                        />
                      </vs-td>

                      <vs-td>
                        <vs-button
                          color="danger"
                          type="relief"
                          v-if="!tr.undeletable"
                          @click="dropField(tr, indextr)"
                        >
                          <vs-icon icon="delete"></vs-icon>
                        </vs-button>
                      </vs-td>
                    </vs-tr>

                    <!-- VALIDATION MESSAGE SECTION -->
                    <vs-tr :key="'validation-' + indextr">

                      <!-- FIELD NAME -->
                      <vs-td>
                        <!-- required -->
                        <i18n path="vuelidate.required" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldName.required" >
                          {{ $t("database.add.row.field.fieldName") }}
                        </i18n>

                        <!-- maxLength -->
                        <i18n path="vuelidate.maxLength" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldName.maxLength" >
                          <template v-slot:field>
                            {{ $t("database.add.row.field.fieldName") }}
                          </template>
                          <template v-slot:length>
                            {{ $v.databaseData.table.$params.maxLength.max }}
                          </template>
                        </i18n>

                        <!-- alphaNumAndUnderscoreValidator -->
                        <i18n path="vuelidate.alphaNumAndUnderscoreValidator" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldName.alphaNumAndUnderscoreValidator" >
                          {{ $t("database.add.row.field.fieldName") }}
                        </i18n>

                        <!-- unique -->
                        <i18n path="vuelidate.unique" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldName.unique" >
                          {{ $t("database.add.row.field.fieldName") }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD TYPE -->
                      <vs-td>
                        <!-- required -->
                        <i18n path="vuelidate.required" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldType.required" >
                          {{ $t("database.add.row.field.fieldType") }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD LENGTH -->
                      <vs-td>
                        <!-- requiredIf -->
                        <i18n path="vuelidate.required" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldLength.required" >
                          {{ $t("database.add.row.field.fieldLength") }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD NULL -->
                      <vs-td></vs-td>

                      <!-- FIELD UNSIGNED -->
                      <vs-td></vs-td>

                      <!-- FIELD INCREMENT -->
                      <vs-td>
                        <!-- distinct -->
                        <i18n path="vuelidate.distinct" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldIncrement.distinct" >
                          {{ $t("database.add.row.field.fieldIncrement") }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD INDEX -->
                      <vs-td>
                        <!-- distinct -->
                        <i18n path="vuelidate.distinct" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldIndex.distinct" >
                          {{ 'primary' }}
                        </i18n>

                        <!-- required -->
                        <i18n path="vuelidate.requiredPrimary" class="is-error" v-if="!$v.databaseData.fields.modifiedFields.$each[indextr].fieldIndex.required" >
                          {{ 'primary' }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD DEFAULT -->
                      <vs-td></vs-td>
                    </vs-tr>
                  </template>
                </template>
              </vs-table>
            </vs-col>
            <vs-col
              vs-w="6"
              class="database-management__footer"
              vs-justify="center"
              vs-align="center"
            >
              <vs-button type="relief" color="primary" @click="addField()">
                <vs-icon icon="add"></vs-icon>
                Add new column
              </vs-button>

              <!-- TODO for future development -->
              <!-- <vs-button type="relief" color="primary" @click="addSoftDeletes()" >
                <vs-icon icon="add"></vs-icon>
                Add soft deletes
              </vs-button> -->
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row vs-align="center">
            <vs-col vs-lg="2">
              <vs-button color="primary" type="relief" @click="submitForm()">
                <vs-icon icon="save"></vs-icon> {{ $t("database.edit.button") }}
              </vs-button>
            </vs-col>
            <vs-col
              vs-lg="10"
              vs-align="center"
              v-if="$v.databaseData.fields.modifiedFields.$dirty"
            >
              <i18n
                path="vuelidate.rowsRequired"
                class="is-error"
                v-if="!$v.databaseData.fields.modifiedFields.required"
              >
              </i18n>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("database.edit.warning.notAllowed") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import {
  required,
  requiredIf,
  maxLength,
  helpers,
} from "vuelidate/lib/validators";

const alphaNumAndUnderscoreValidator = helpers.regex(
  "alphaNumAndDot",
  /^[a-zA-Z\d_]*$/i
);

const unique = (group, key, keyValue) => {
  return (value) => {
    if (value === '') return true

    const found = group.filter((item) => {
      if (key) {
        if (keyValue) {
          return item[key] == keyValue
        }

        return item[key] == value;
      }
      return item == value;
    });

    return found.length <= 1;
  };
};

export default {
  name: "DatabaseManagementEdit",
  components: {},
  data: () => ({
    breadcrumb: [],
    databaseData: {
      table: {
        currentName: "",
        modifiedName: "",
      },
      fields: {
        currentFields: [],
        modifiedFields: [],
      },
    },
    isCanEdit: false,
    fieldTypeList: [],
  }),
  validations() {
    return {
      databaseData: {
        table: {
          modifiedName: {
            required,
            maxLength: maxLength(64),
            alphaNumAndUnderscoreValidator,
          }
        },
        fields: {
          modifiedFields: {
            required,
            $each: {
              fieldName: {
                required,
                maxLength: maxLength(64),
                alphaNumAndUnderscoreValidator,
                unique: unique(this.databaseData.fields.modifiedFields, "fieldName"),
              },
              fieldType: {
                required,
              },
              fieldLength: {
                required: requiredIf (function (value) {
                  return value.fieldType == "double" || value.fieldType == "decimal" || value.fieldType == "float" || value.fieldType == "varchar" || value.fieldType == "char" || value.fieldType == "set" || value.fieldType == "enum"
                })
              },
              fieldIncrement: {
                distinct: unique(this.databaseData.fields.modifiedFields, "fieldIncrement", true)
              },
              fieldIndex: {
                distinct: unique(this.databaseData.fields.modifiedFields, "fieldIndex", "primary"),
                required: requiredIf (function (value) {
                  return value.fieldIncrement === true
                }),
              },
            },
          },
          currentFields: {
            required
          }
        },
      },
    };
  },
  computed: {
    fieldIndexList: {
      get() {
        return this.$databaseHelper.getMigrationIndexList();
      },
    },
  },
  mounted() {
    this.getInfoTable();
    this.getDbmsFieldType();
    this.getIsCanEdit();
  },
  methods: {
    getDbmsFieldType() {
      this.$api.badasoDatabase
        .getType()
        .then((response) => {
          this.fieldTypeList = JSON.parse(response.data);
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },

    getIsCanEdit() {
      this.$api.badasoEntity
        .browse({
          slug: this.$route.params.tableName,
        })
        .then((response) => {
          this.$closeLoader();
          this.isCanEdit = false;
        })
        .catch((error) => {
          this.$closeLoader();
          this.isCanEdit = true;
        });
    },

    getFieldLength(column) {
      if (
        column.type == "decimal" ||
        column.type == "double" ||
        column.type == "float"
      ) {
        return column.precision + "," + column.scale;
      } else if (column.type == "enum" || column.type == "set") {
        return column.options.join();
      } else if (column.type == "date" || column.type == "timestamp" || column.type == "longblob") {
        return null;
      } else {
        return column.length;
      }
    },

    getInfoTable() {
      this.$openLoader();
      this.$api.badasoDatabase
        .read({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          let data = response.data.columns;
          for (const [key, column] of Object.entries(data)) {
            let id = this.$helper.uuid();
            this.databaseData.fields.modifiedFields.push({
              id: id,
              fieldName: column.name,
              fieldType: column.type === 'longblob' ? 'blob' : column.type,
              fieldLength: this.getFieldLength(column),
              fieldNull: column.notnull ? false : true,
              fieldAttribute: column.unsigned,
              fieldIncrement: column.autoincrement,
              fieldIndex:
                Object.keys(column.indexes).length > 0
                  ? Object.values(column.indexes)[0]
                      .type.toString()
                      .toLowerCase()
                  : null,
              fieldDefault: column.default,
              modifyType: [],
              undeletable: column.name === 'created_at' || column.name === 'updated_at' ? true : false
            });

            this.databaseData.fields.currentFields.push({
              id: id,
              fieldName: column.name,
              fieldType: column.type === 'longblob' ? 'blob' : column.type,
              fieldLength: this.getFieldLength(column),
              fieldNull: column.notnull ? false : true,
              fieldAttribute: column.unsigned,
              fieldIncrement: column.autoincrement,
              fieldIndex:
                Object.keys(column.indexes).length > 0
                  ? Object.values(column.indexes)[0]
                      .type.toString()
                      .toLowerCase()
                  : null,
              fieldDefault: column.default
            });
          }

          this.databaseData.table.currentName = this.$route.params.tableName;
          this.databaseData.table.modifiedName = this.$route.params.tableName;
          this.$closeLoader();
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },

    submitForm() {
      this.$v.databaseData.$touch();
      if (!this.$v.databaseData.$invalid) {
        this.$openLoader();

        this.$api.badasoDatabase
          .edit(this.databaseData)
          .then((response) => {
            this.$closeLoader();
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: response.message,
              color: "success",
            });
            this.$store.commit("badaso/FETCH_MENU");
            this.$store.commit("badaso/FETCH_USER");
            this.$router.push({ name: "DatabaseManagementBrowse" });
          })
          .catch((error) => {
            let message = error.message;
            this.$closeLoader();
            this.$v.$reset();

            if (error.errors.table) {
              message = error.errors.table[0];
            }

            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: message ? message : this.$t('database.warning.errorOnRequest'),
              color: "danger",
            });
          });
      } else {
        if (this.$v.databaseData.fields.modifiedFields.$invalid) {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: this.$t("database.warning.empty"),
            color: "danger",
          });
        } else if (this.$v.databaseData.$invalid) {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: this.$t("database.warning.invalid"),
            color: "danger",
          });
        }
      }
    },

    addField() {
      let index = this.databaseData.fields.modifiedFields.map(row => row.undeletable).indexOf(true)
      this.databaseData.fields.modifiedFields.splice(index, 0, {
        id: this.$helper.uuid(),
        fieldName: "",
        fieldType: "",
        fieldLength: null,
        fieldNull: false,
        fieldAttribute: false,
        fieldIncrement: false,
        fieldIndex: null,
        fieldDefault: "",
        modifyType: [
          'CREATE'
        ]
      });
    },

    findFieldOnRows(fieldName) {
      let found = false;

      for (let index = 0; index < this.databaseData.fields.modifiedFields.length; index++) {
        const element = this.databaseData.fields.modifiedFields[index];
        if (element.fieldName == fieldName) {
          found = true;
          break;
        }
      }

      return found;
    },

    // addTimestamps() {
    //   if (this.findFieldOnRows("created_at")) {
    //     this.$vs.notify({
    //       title: this.$t("alert.danger"),
    //       text: this.$t("database.warning.exists", { 0: "created_at" }),
    //       color: "danger",
    //     });
    //   } else {
    //     this.databaseData.fields.modifiedFields.push({
    //       id: this.$helper.uuid(),
    //       fieldName: "created_at",
    //       fieldType: "timestamp",
    //       fieldLength: null,
    //       fieldNull: true,
    //       fieldAttribute: false,
    //       fieldIncrement: false,
    //       fieldIndex: null,
    //       fieldDefault: null,
    //       modifyType: [
    //         'CREATE'
    //       ]
    //     });
    //   }

    //   if (this.findFieldOnRows("updated_at")) {
    //     this.$vs.notify({
    //       title: this.$t("alert.danger"),
    //       text: this.$t("database.warning.exists", { 0: "updated_at" }),
    //       color: "danger",
    //     });
    //   } else {
    //     this.databaseData.fields.modifiedFields.push({
    //       id: this.$helper.uuid(),
    //       fieldName: "updated_at",
    //       fieldType: "timestamp",
    //       fieldLength: null,
    //       fieldNull: true,
    //       fieldAttribute: false,
    //       fieldIncrement: false,
    //       fieldIndex: null,
    //       fieldDefault: null,
    //       modifyType: [
    //         'CREATE'
    //       ]
    //     });
    //   }
    // },

    dropField(item, index) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: () => this.saveDrop(item, index),
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
      });
    },

    saveDrop(item, index) {
      this.databaseData.fields.modifiedFields.splice(index, 1)
    },

    alterFieldProperty(item, event, eventType, field, indexRow) {
      let oldValue = item[field]
      let newValue = null

      if (field === 'fieldType' || field === 'fieldIndex') {
        newValue = event
      } else if (field === 'fieldNull' || field === 'fieldIncrement' || field === 'fieldAttribute') {
        newValue = event.target.checked
      } else {
        newValue = event.target.value
      }

      if (item.modifyType.includes('CREATE')) {
        item[field] = newValue;
      } else {
        var isNew = true

        if (item.modifyType.includes(eventType)) {
          isNew = false
        }

        if (isNew) {
          item.modifyType.push(eventType);
        }

        for (const value of this.databaseData.fields.currentFields) {
          if (item.id === value.id) {
            if (newValue == value[field]) {
              let itemIndex = item.modifyType.indexOf(eventType);
              
              if (itemIndex > -1) {
                item.modifyType.splice(itemIndex, 1);
              }
            }
          }
        }

        if (newValue === "") {
          newValue = null
        }

        item[field] = newValue;
      }
    },
  },
};
</script>
