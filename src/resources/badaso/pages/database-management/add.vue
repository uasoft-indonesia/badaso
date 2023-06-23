<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('add_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("database.add.title") }}</h3>
          </div>
          <vs-row vs-align="center">
            <vs-col vs-lg="12" vs-align="center">
              <badaso-text
                v-model="databaseData.table"
                size="12"
                :label="$t('database.add.field.table')"
                :placeholder="$t('database.add.field.table')"
                required
                autofocus
              >
              </badaso-text>
            </vs-col>
            <vs-col vs-lg="12">
              <div v-if="$v.databaseData.table.$dirty">
                <i18n
                  path="vuelidate.required"
                  class="is-error"
                  v-if="!$v.databaseData.table.required"
                >
                  {{ $t("database.add.row.field.tableName") }}
                </i18n>

                <i18n
                  path="vuelidate.alphaNumAndUnderscoreValidator"
                  class="is-error"
                  v-if="!$v.databaseData.table.alphaNumAndUnderscoreValidator"
                >
                  {{ $t("database.add.row.field.tableName") }}
                </i18n>

                <i18n
                  path="vuelidate.maxLength"
                  class="is-error"
                  v-if="!$v.databaseData.table.maxLength"
                >
                  <template v-slot:field>
                    {{ $t("database.add.row.field.tableName") }}
                  </template>
                  <template v-slot:length>
                    {{ $v.databaseData.table.$params.maxLength.max }}
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
            <h3>{{ $t("database.add.row.title") }}</h3>
            <span class="is-error">
              <i18n path="database.warning.docs">
                <a
                  target="_blank"
                  href="https://badaso-docs.uatech.co.id/core-concept/database-management"
                  >docs</a
                >
              </i18n>
            </span>
          </div>
          <vs-row vs-justify="center" vs-align="center">
            <vs-col col-lg="12">
              <vs-table :data="databaseData.rows">
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
                          :disabled="tr.undeletable"
                          v-model="tr.fieldName"
                          @input="renameForeignkey(tr)"
                        />
                      </vs-td>

                      <vs-td :data="tr.fieldType">
                        <vs-select
                          class="database-management__field-type"
                          v-model="tr.fieldType"
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
                          v-model="tr.fieldLength"
                          :disabled="tr.undeletable"
                        />
                      </vs-td>

                      <vs-td :data="tr.fieldNull">
                        <vs-checkbox
                          v-model="tr.fieldNull"
                          :disabled="tr.undeletable"
                        ></vs-checkbox>
                      </vs-td>

                      <vs-td :data="tr.fieldAttribute">
                        <vs-checkbox
                          v-model="tr.fieldAttribute"
                          :disabled="tr.undeletable"
                        ></vs-checkbox>
                      </vs-td>

                      <vs-td :data="tr.fieldIncrement">
                        <vs-checkbox
                          v-model="tr.fieldIncrement"
                          :disabled="tr.undeletable"
                        ></vs-checkbox>
                      </vs-td>

                      <vs-td :data="tr.fieldIndex">
                        <vs-select
                          class="database-management__field-index"
                          v-model="tr.fieldIndex"
                          :disabled="tr.undeletable"
                          @change="setFieldIndex(tr)"
                        >
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
                          v-model="tr.fieldDefault"
                          :disabled="tr.undeletable"
                        />
                      </vs-td>

                      <vs-td>
                        <div class="database-management__button-group">
                          <vs-button
                            color="danger"
                            type="relief"
                            v-if="!tr.undeletable"
                            @click="dropField(indextr, tr)"
                          >
                            <vs-icon icon="delete"></vs-icon>
                          </vs-button>
                          <vs-button
                            color="primary"
                            type="relief"
                            v-if="!tr.undeletable && tr.fieldIndex == 'foreign'"
                            @click="openRelationDialog(tr)"
                          >
                            <vs-icon icon="link"></vs-icon>
                          </vs-button>
                        </div>
                      </vs-td>
                    </vs-tr>

                    <!-- VALIDATION MESSAGE SECTION -->
                    <vs-tr :key="'validation-' + indextr">
                      <!-- FIELD NAME -->
                      <vs-td>
                        <!-- required -->
                        <i18n
                          path="vuelidate.required"
                          class="is-error"
                          v-if="
                            !$v.databaseData.rows.$each[indextr].fieldName
                              .required
                          "
                        >
                          {{ $t("database.add.row.field.fieldName") }}
                        </i18n>

                        <!-- maxLength -->
                        <i18n
                          path="vuelidate.maxLength"
                          class="is-error"
                          v-if="
                            !$v.databaseData.rows.$each[indextr].fieldName
                              .maxLength
                          "
                        >
                          <template v-slot:field>
                            {{ $t("database.add.row.field.fieldName") }}
                          </template>
                          <template v-slot:length>
                            {{ $v.databaseData.table.$params.maxLength.max }}
                          </template>
                        </i18n>

                        <!-- alphaNumAndUnderscoreValidator -->
                        <i18n
                          path="vuelidate.alphaNumAndUnderscoreValidator"
                          class="is-error"
                          v-if="
                            !$v.databaseData.rows.$each[indextr].fieldName
                              .alphaNumAndUnderscoreValidator
                          "
                        >
                          {{ $t("database.add.row.field.fieldName") }}
                        </i18n>

                        <!-- unique -->
                        <i18n
                          path="vuelidate.unique"
                          class="is-error"
                          v-if="
                            !$v.databaseData.rows.$each[indextr].fieldName
                              .unique
                          "
                        >
                          {{ $t("database.add.row.field.fieldName") }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD TYPE -->
                      <vs-td>
                        <!-- required -->
                        <i18n
                          path="vuelidate.required"
                          class="is-error"
                          v-if="
                            !$v.databaseData.rows.$each[indextr].fieldType
                              .required
                          "
                        >
                          {{ $t("database.add.row.field.fieldType") }}
                        </i18n>
                      </vs-td>

                      <!-- FIELD LENGTH -->
                      <vs-td>
                        <!-- requiredIf -->
                        <i18n
                          path="vuelidate.required"
                          class="is-error"
                          v-if="
                            !$v.databaseData.rows.$each[indextr].fieldLength
                              .required
                          "
                        >
                          {{ $t("database.add.row.field.fieldLength") }}
                        </i18n>
                      </vs-td>
                    </vs-tr>
                  </template>
                </template>
              </vs-table>
              <vs-prompt
                type="confirm"
                @accept="setRelation"
                @cancel="cancelRelationDialog"
                :is-valid="!$v.databaseData.relations.$each.$invalid"
                :active.sync="relationDialog"
                title="Relationship"
                class="database-management__relationship-prompt"
              >
                <vs-row
                  vs-type="grid"
                  class="database-management__relationship-dialog"
                >
                  <vs-col vs-w="12">
                    <h3>Source Table</h3>
                  </vs-col>
                  <vs-col vs-w="12">
                    <vs-input
                      v-if="selectedField"
                      disabled
                      label="Field"
                      placeholder="Field"
                      v-model="
                        databaseData.relations[selectedField].sourceField
                      "
                    />
                  </vs-col>
                  <vs-col vs-w="12">
                    <h3>Target Table</h3>
                  </vs-col>
                  <vs-col vs-w="12">
                    <vs-select
                      label="Table"
                      v-if="selectedField"
                      @change="fetchTableFields"
                      width="100%"
                      v-model="
                        databaseData.relations[selectedField].targetTable
                      "
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.value"
                        v-for="(item, index) in tables"
                      />
                    </vs-select>
                  </vs-col>
                  <vs-col vs-w="12">
                    <vs-select
                      label="Field"
                      v-if="selectedField"
                      :disabled="fields.length == 0"
                      width="100%"
                      v-model="
                        databaseData.relations[selectedField].targetField
                      "
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.value"
                        v-for="(item, index) in fields"
                      />
                    </vs-select>
                  </vs-col>
                  <vs-col vs-w="12">
                    <h3>Type</h3>
                  </vs-col>
                  <vs-col vs-w="12">
                    <vs-select
                      label="On Delete"
                      v-if="selectedField"
                      width="100%"
                      v-model="databaseData.relations[selectedField].onDelete"
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in relationType"
                      />
                    </vs-select>
                  </vs-col>
                  <vs-col vs-w="12">
                    <vs-select
                      label="On Update"
                      v-if="selectedField"
                      width="100%"
                      v-model="databaseData.relations[selectedField].onUpdate"
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in relationType"
                      />
                    </vs-select>
                  </vs-col>
                </vs-row>
              </vs-prompt>
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
              <vs-button
                type="relief"
                color="primary"
                @click="addSoftDeletes()"
              >
                <vs-icon icon="add"></vs-icon>
                Add soft deletes
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row vs-align="center">
            <vs-col vs-lg="2">
              <vs-button color="primary" type="relief" @click="submitForm()">
                <vs-icon icon="save"></vs-icon> {{ $t("database.add.button") }}
              </vs-button>
            </vs-col>
            <vs-col
              vs-lg="10"
              vs-align="center"
              v-if="$v.databaseData.rows.$dirty"
            >
              <i18n
                path="vuelidate.rowsRequired"
                class="is-error"
                v-if="!$v.databaseData.rows.required"
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
              <h3>{{ $t("database.warning.notAllowed") }}</h3>
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

const unique = (group, key) => {
  return (value) => {
    if (value == "") return true;

    const found = group.filter((item) => {
      if (key) {
        return item[key] == value;
      }
      return item == value;
    });
    return found.length <= 1;
  };
};

export default {
  name: "DatabaseManagementAdd",
  components: {},
  data: () => ({
    breadcrumb: [],
    databaseData: {
      table: "",
      rows: [],
      relations: {},
    },
    fieldTypeList: [],
    relationDialog: false,
    tables: [],
    fields: [],
    selectedField: "",
  }),
  validations() {
    return {
      databaseData: {
        relations: {
          $each: {
            sourceField: {
              required,
            },
            targetTable: {
              required,
            },
            targetField: {
              required,
            },
          },
        },
        table: {
          required,
          maxLength: maxLength(64),
          alphaNumAndUnderscoreValidator,
        },
        rows: {
          required,
          $each: {
            fieldName: {
              required,
              maxLength: maxLength(64),
              alphaNumAndUnderscoreValidator,
              unique: unique(this.databaseData.rows, "fieldName"),
            },
            fieldType: {
              required,
            },
            fieldLength: {
              required: requiredIf(function (value) {
                return (
                  value.fieldType == "double" ||
                  value.fieldType == "decimal" ||
                  value.fieldType == "float" ||
                  value.fieldType == "varchar" ||
                  value.fieldType == "char" ||
                  value.fieldType == "set" ||
                  value.fieldType == "enum"
                );
              }),
            },
          },
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
    relationType() {
      return this.$databaseHelper.getForeignConstraint();
    },
  },
  mounted() {
    this.getDbmsFieldType();
    this.insertIdToRows();
  },
  methods: {
    renameForeignkey(item) {
      if (this.databaseData.relations[item.id]) {
        const newVal = item.fieldName;
        const oldVal = this.databaseData.relations[item.id].sourceField || null;
        if (newVal !== oldVal) {
          this.databaseData.relations[item.id].sourceField = newVal;
        }
      }
    },
    setFieldIndex(item) {
      if (item.fieldIndex == "foreign") {
        this.$set(this.databaseData.relations, item.id, {
          sourceField: item.fieldName,
          targetTable: "",
          targetField: "",
          onDelete: null,
          onUpdate: null,
        });
      } else {
        this.$delete(this.databaseData.relations, item.id);
      }
    },
    setRelation() {
      this.$v.databaseData.relations.$touch();
      if (!this.$v.databaseData.relations.$invalid) {
        this.relationDialog = false;
      }
    },
    fetchTableFields() {
      this.$openLoader();
      this.$api.badasoTable
        .read({
          table: this.databaseData.relations[this.selectedField].targetTable,
        })
        .then((response) => {
          this.$closeLoader();
          this.fields = response.data.tableFields;
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
    openRelationDialog(item) {
      this.selectedField = item.id;
      this.relationDialog = true;
      this.getTableList();
    },
    cancelRelationDialog() {
      this.$v.databaseData.relations.$touch();
      if (this.$v.databaseData.relations.$invalid) {
        this.relationDialog = false;
        this.databaseData.relations[this.selectedField].targetTable = "";
        this.databaseData.relations[this.selectedField].targetField = "";
        this.databaseData.relations[this.selectedField].onDelete = "";
        this.databaseData.relations[this.selectedField].onUpdate = "";
      }
    },
    getTableList() {
      this.$openLoader();
      this.$api.badasoCrud
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.tables = response.data.tablesWithCrudData.map((table) => {
            return {
              value: table.tableName,
            };
          });
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
    getDbmsFieldType() {
      this.$openLoader();
      this.$api.badasoDatabase
        .getType()
        .then((response) => {
          this.$closeLoader();
          this.fieldTypeList = JSON.parse(response.data);
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
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.$v.$reset();
        this.$openLoader();
        this.$api.badasoDatabase
          .add(this.databaseData)
          .then((response) => {
            this.$closeLoader();
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: response.data,
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

            if (error.errors["rows.0.fieldType"]) {
              message = error.errors["rows.0.fieldType"][0];
            }

            if (error.errors.code.indexOf("HY000") == 0) {
              this.$vs.notify({
                title: this.$t("alert.danger"),
                text: this.$t("database.edit.warning.fieldAttUnsigned", {
                  0: `<a href="https://badaso-docs.uatech.co.id/core-concept/database-management#create-relationship-table" target="_blank"><b>${this.$t(
                    "database.edit.warning.visitDocs"
                  )}<b></a>`,
                }),
                color: "danger",
              });
            } else {
              this.$vs.notify({
                title: this.$t("alert.danger"),
                text: message || this.$t("database.warning.errorOnRequest"),
                color: "danger",
              });
            }
          });
      } else {
        if (this.$v.databaseData.rows.$invalid) {
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
      const index = this.databaseData.rows
        .map((row) => row.indexes)
        .indexOf(true);
      this.databaseData.rows.splice(index, 0, {
        id: this.$helper.uuid(),
        fieldName: "",
        fieldType: "",
        fieldLength: null,
        fieldNull: false,
        fieldAttribute: false,
        fieldIncrement: false,
        fieldIndex: null,
        fieldDefault: "",
      });
    },

    findFieldOnRows(fieldName) {
      let found = false;

      for (let index = 0; index < this.databaseData.rows.length; index++) {
        const element = this.databaseData.rows[index];
        if (element.fieldName == fieldName) {
          found = true;
          break;
        }
      }

      return found;
    },

    addSoftDeletes() {
      if (this.findFieldOnRows("deleted_at")) {
        this.$vs.notify({
          title: this.$t("alert.danger"),
          text: this.$t("database.warning.exists", { 0: "deleted_at" }),
          color: "danger",
        });
      } else {
        this.databaseData.rows.push({
          fieldName: "deleted_at",
          fieldType: "timestamp",
          fieldLength: null,
          fieldNull: true,
          fieldAttribute: false,
          fieldIncrement: false,
          fieldIndex: null,
          fieldDefault: null,
        });
      }
    },

    dropField(index, item) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: () => {
          this.databaseData.rows.splice(index, 1);
          this.$delete(this.databaseData.relations, item.fieldName);
        },
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
      });
    },

    insertIdToRows() {
      this.databaseData.rows.push(
        {
          id: "id",
          fieldName: "id",
          fieldType: "bigint",
          fieldLength: null,
          fieldNull: false,
          fieldAttribute: true,
          fieldIncrement: true,
          fieldIndex: "primary",
          fieldDefault: null,
          undeletable: true,
        },
        {
          fieldName: "created_at",
          fieldType: "timestamp",
          fieldLength: null,
          fieldNull: true,
          fieldAttribute: false,
          fieldIncrement: false,
          fieldIndex: null,
          fieldDefault: null,
          undeletable: true,
          indexes: true,
        },
        {
          fieldName: "updated_at",
          fieldType: "timestamp",
          fieldLength: null,
          fieldNull: true,
          fieldAttribute: false,
          fieldIncrement: false,
          fieldIndex: null,
          fieldDefault: null,
          undeletable: true,
        }
      );
    },
  },
};
</script>
