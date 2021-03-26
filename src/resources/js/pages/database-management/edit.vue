<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_database')">
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
            <vs-col vs-align="center" vs-lg="3">
              <vs-checkbox v-model="databaseData.timestamp">{{
                $t("database.edit.row.field.timestamp")
              }}</vs-checkbox>
            </vs-col>
            <vs-col vs-lg="12">
              <div
                v-if="$v.databaseData.table.modifiedName.$dirty"
                class="d-inline-grid"
              >
                <i18n
                  path="vuelidate.required"
                  style="color: rgba(var(--vs-danger),1)"
                  v-if="!$v.databaseData.table.modifiedName.required"
                >
                  {{ $t("database.edit.row.field.tableName") }} <br />
                </i18n>

                <i18n
                  path="vuelidate.alphaNumAndUnderscoreValidator"
                  style="color: rgba(var(--vs-danger),1)"
                  v-if="
                    !$v.databaseData.table.modifiedName
                      .alphaNumAndUnderscoreValidator
                  "
                >
                  {{ $t("database.edit.row.field.tableName") }} <br />
                </i18n>

                <i18n
                  path="vuelidate.maxLength"
                  style="color: rgba(var(--vs-danger),1)"
                  v-if="!$v.databaseData.table.modifiedName.maxLength"
                >
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
          <vs-row>
            <vs-col col-lg="12" style="overflow-x: auto">
              <table class="table">
                <thead>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldName") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldType") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldLength") }}
                  </th>
                  <th style="min-width: 100px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldIncrement") }}
                  </th>
                  <th style="min-width: 100px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldNull") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldDefault") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.asDefined") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldIndex") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.fieldAttribute") }}
                  </th>
                  <th style="min-width: 150px; word-wrap: nowrap;">
                    {{ $t("database.edit.row.field.action") }}
                  </th>
                </thead>
                <tr
                  :key="index"
                  v-for="(item, index) in databaseData.fields.currentFields"
                >
                  <td>
                    {{ item.fieldName }}
                  </td>
                  <td>
                    {{ item.fieldType }}
                  </td>
                  <td>
                    {{ item.fieldLength }}
                  </td>
                  <td>
                    {{ item.fieldIncrement }}
                  </td>
                  <td>
                    {{ item.fieldNull }}
                  </td>
                  <td>
                    {{ item.fieldDefault }}
                  </td>
                  <td>
                    {{ item.asDefined }}
                  </td>
                  <td>
                    {{ item.fieldIndex }}
                  </td>
                  <td>
                    {{ item.fieldAttribute }}
                  </td>
                  <td>
                    <vs-button
                      color="success"
                      type="relief"
                      @click="editField(item, index)"
                    >
                      <vs-icon icon="edit"></vs-icon>
                    </vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click="dropField(item, index)"
                    >
                      <vs-icon icon="delete"></vs-icon>
                    </vs-button>
                  </td>
                </tr>
                <tr>
                  <td>
                    <vs-input
                      class="inputx mb-2"
                      :placeholder="$t('database.edit.row.field.fieldName')"
                      v-model="fields.fieldName"
                    />

                    <div
                      v-if="$v.fields.fieldName.$dirty"
                      class="d-inline-grid"
                    >
                      <i18n
                        path="vuelidate.required"
                        style="color: rgba(var(--vs-danger),1)"
                        v-if="!$v.fields.fieldName.required"
                      >
                        {{ $t("database.edit.row.field.fieldName") }}
                      </i18n>

                      <i18n
                        path="vuelidate.alphaNumAndUnderscoreValidator"
                        style="color: rgba(var(--vs-danger),1)"
                        v-if="
                          !$v.fields.fieldName.alphaNumAndUnderscoreValidator
                        "
                      >
                        {{ $t("database.edit.row.field.fieldName") }}
                      </i18n>

                      <i18n
                        path="vuelidate.maxLength"
                        style="color: rgba(var(--vs-danger),1)"
                        v-if="!$v.fields.fieldName.maxLength"
                      >
                        <template v-slot:field>
                          {{ $t("database.edit.row.field.fieldName") }}
                        </template>
                        <template v-slot:length>
                          {{ $v.fields.fieldName.$params.maxLength.max }}
                        </template>
                      </i18n>
                    </div>
                  </td>

                  <td>
                    <vs-select
                      class="w-100 selectExample mb-2"
                      v-model="fields.fieldType"
                    >
                      <div :key="index" v-for="(item, index) in fieldTypeList">
                        <vs-select-group :title="item.title" v-if="item.group">
                          <vs-select-item
                            :key="index"
                            :value="item.value"
                            :text="item.label"
                            v-for="(item, index) in item.group"
                          />
                        </vs-select-group>
                      </div>
                    </vs-select>

                    <div
                      v-if="$v.fields.fieldType.$dirty"
                      class="d-inline-grid"
                    >
                      <i18n
                        path="vuelidate.required"
                        style="color: rgba(var(--vs-danger),1)"
                        v-if="!$v.fields.fieldType.required"
                      >
                        {{ $t("database.edit.row.field.fieldType") }}
                      </i18n>
                    </div>
                  </td>

                  <td>
                    <vs-input
                      class="inputx"
                      :placeholder="$t('database.edit.row.field.fieldLength')"
                      v-model="fields.fieldLength"
                    />

                    <div
                      v-if="$v.fields.fieldLength.$dirty"
                      class="d-inline-grid"
                    >
                      <i18n
                        path="vuelidate.required"
                        style="color: rgba(var(--vs-danger),1)"
                        v-if="!$v.fields.fieldLength.required"
                      >
                        {{ $t("database.edit.row.field.fieldLength") }}
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldIncrement"
                      class="mb-1"
                      style="justify-content: start;"
                    ></vs-checkbox>
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldNull"
                      class="mb-1"
                      style="justify-content: start;"
                    ></vs-checkbox>
                  </td>
                  <td>
                    <vs-select
                      class="w-100 selectExample mb-2"
                      v-model="fields.fieldDefault"
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldDefaultList"
                      />
                    </vs-select>
                  </td>
                  <td>
                    <vs-input
                      v-if="fields.fieldDefault == 'as_defined'"
                      class="inputx"
                      :placeholder="$t('database.edit.row.field.fieldDefault')"
                      v-model="fields.asDefined"
                    />

                    <div
                      v-if="$v.fields.asDefined.$dirty"
                      class="d-inline-grid"
                    >
                      <i18n
                        path="vuelidate.required"
                        style="color: rgba(var(--vs-danger),1)"
                        v-if="!$v.fields.asDefined.required"
                      >
                        {{ $t("database.edit.row.field.fieldDefault") }}
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-select
                      class="w-100 selectExample"
                      v-model="fields.fieldIndex"
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldIndexList"
                      />
                    </vs-select>
                  </td>
                  <td>
                    <vs-select
                      class="w-100 selectExample"
                      v-model="fields.fieldAttribute"
                    >
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldAttributeList"
                      />
                    </vs-select>
                  </td>
                  <td>
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.prevent="setField()"
                    >
                      {{ $t("database.edit.row.field.add") }}
                    </vs-button>
                  </td>
                </tr>
              </table>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row vs-align="center">
            <vs-col vs-lg="2">
              <vs-button color="primary" type="relief" @click="submitForm()">
                <vs-icon icon="save"></vs-icon> {{ $t("database.edit.button") }}
              </vs-button>
            </vs-col>
            <vs-col
              vs-lg="10"
              vs-align="center"
              v-if="$v.databaseData.fields.currentFields.$dirty"
              class="d-inline-grid"
            >
              <i18n
                path="vuelidate.rowsRequired"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.databaseData.fields.currentFields.required"
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

    <!-- EDIT FIELD FORM -->

    <vs-popup
      color="success"
      classContent="popup-example"
      :active.sync="editDialog"
      title="Edit"
      :is-valid="!$v.edit.$invalid"
    >
      <div class="con-exemple-prompt">
        <vs-row vs-align="center">
          <vs-col vs-lg="12">
            <vs-input
              class="inputx mb-2"
              :label="$t('database.edit.row.field.fieldName')"
              :placeholder="$t('database.edit.row.field.fieldName')"
              v-model="edit.fieldName"
            />

            <div v-if="$v.edit.fieldName.$dirty" class="d-inline-grid">
              <i18n
                path="vuelidate.required"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.edit.fieldName.required"
              >
                {{ $t("database.edit.row.field.fieldName") }}
              </i18n>

              <i18n
                path="vuelidate.alphaNumAndUnderscoreValidator"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.edit.fieldName.alphaNumAndUnderscoreValidator"
              >
                {{ $t("database.edit.row.field.fieldName") }}
              </i18n>

              <i18n
                path="vuelidate.maxLength"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.edit.fieldName.maxLength"
              >
                <template v-slot:field>
                  {{ $t("database.edit.row.field.fieldName") }}
                </template>
                <template v-slot:length>
                  {{ $v.edit.fieldName.$params.maxLength.max }}
                </template>
              </i18n>
            </div>
          </vs-col>

          <vs-col vs-lg="6">
            <vs-input
              class="inputx mb-2"
              :placeholder="$t('database.edit.row.field.fieldLength')"
              :label="$t('database.edit.row.field.fieldLength')"
              v-model="edit.fieldLength"
            />

            <div v-if="$v.edit.fieldLength.$dirty" class="d-inline-grid">
              <i18n
                path="vuelidate.required"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.edit.fieldLength.required"
              >
                {{ $t("database.edit.row.field.fieldLength") }}
              </i18n>
            </div>
          </vs-col>

          <vs-col vs-lg="6">
            <vs-select
              class="w-100 selectExample mb-2"
              :label="$t('database.edit.row.field.fieldType')"
              v-model="edit.fieldType"
            >
              <div :key="index" v-for="(item, index) in fieldTypeList">
                <vs-select-group :title="item.title" v-if="item.group">
                  <vs-select-item
                    :key="index"
                    :value="item.value"
                    :text="item.label"
                    v-for="(item, index) in item.group"
                  />
                </vs-select-group>
              </div>
            </vs-select>

            <div v-if="$v.edit.fieldType.$dirty" class="d-inline-grid">
              <i18n
                path="vuelidate.required"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.edit.fieldType.required"
              >
                {{ $t("database.edit.row.field.fieldType") }}
              </i18n>
            </div>
          </vs-col>

          <vs-col vs-lg="3" vs-align="center">
            <vs-checkbox
              v-model="edit.fieldNull"
              class="mb-4 mt-4"
              style="justify-content: start;"
            >
              {{ $t("database.edit.row.field.fieldNull") }}
            </vs-checkbox>
          </vs-col>

          <vs-col vs-lg="3" vs-align="center">
            <vs-checkbox
              v-model="edit.fieldIncrement"
              class="mb-4 mt-4"
              style="justify-content: start;"
              >{{ $t("database.edit.row.field.fieldIncrement") }}</vs-checkbox
            >
          </vs-col>

          <vs-col vs-lg="6">
            <vs-select
              class="w-100 selectExample mb-2"
              :label="$t('database.edit.row.field.fieldDefault')"
              v-model="edit.fieldDefault"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldDefaultList"
              />
            </vs-select>
            <vs-input
              v-if="edit.fieldDefault == 'as_defined'"
              class="inputx mb-2"
              v-model="edit.asDefined"
            />

            <div v-if="$v.edit.asDefined.$dirty" class="d-inline-grid">
              <i18n
                path="vuelidate.required"
                style="color: rgba(var(--vs-danger),1)"
                v-if="!$v.edit.asDefined.required"
              >
                {{ $t("database.edit.row.field.fieldDefault") }}
              </i18n>
            </div>
          </vs-col>

          <vs-col vs-lg="6">
            <vs-select
              class="w-100 selectExample mb-2"
              :label="$t('database.edit.row.field.fieldIndex')"
              v-model="edit.fieldIndex"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldIndexList"
              />
            </vs-select>
          </vs-col>

          <vs-col vs-lg="6" vs-xs="12">
            <vs-select
              class="w-100 selectExample mb-2 w-100"
              :label="$t('database.edit.row.field.fieldAttribute')"
              v-model="edit.fieldAttribute"
            >
              <vs-select-item
                :key="index"
                :value="item.value"
                :text="item.label"
                v-for="(item, index) in fieldAttributeList"
              />
            </vs-select>
          </vs-col>
        </vs-row>
        <vs-row vs-type="flex" vs-justify="space-between">
          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
            <vs-button
              class="btn-block"
              color="danger"
              @click="editDialog = false"
              type="relief"
              >{{ $t("action.delete.cancel") }}</vs-button
            >
          </vs-col>
          <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
            <vs-button
              class="btn-block"
              color="primary"
              @click="saveEdit()"
              type="relief"
              >{{ $t("action.delete.accept") }}</vs-button
            >
          </vs-col>
        </vs-row>
      </div>
    </vs-popup>

    <!-- DROP FIELD DIALOG -->

    <vs-prompt
      color="danger"
      @accept="saveDrop()"
      :active.sync="deleteDialog"
      title="Delete"
      :accept-text="$t('action.delete.accept')"
      :cancel-text="$t('action.delete.cancel')"
    >
      <div class="con-exemple-prompt">
        {{ $t("database.edit.row.drop") }}
      </div>
    </vs-prompt>
  </div>
</template>

<script>
import draggable from "vuedraggable";
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

export default {
  name: "DatabaseManagementEdit",
  components: {
    draggable,
  },
  data: () => ({
    breadcrumb: [],
    fields: {
      fieldName: "",
      fieldType: "",
      fieldLength: "",
      fieldDefault: null,
      fieldNull: false,
      fieldIndex: null,
      fieldAttribute: null,
      fieldIncrement: false,
      asDefined: null,
    },
    databaseData: {
      table: {
        currentName: "",
        modifiedName: "",
      },
      timestamp: true,
      fields: {
        currentFields: [],
        modifiedFields: [],
      },
    },
    editDialog: false,
    deleteDialog: false,
    willEdit: "",
    willDelete: "",
    deletedItem: "",
    edit: {
      fieldName: "",
      fieldType: "",
      fieldLength: null,
      fieldDefault: null,
      fieldNull: false,
      fieldIndex: null,
      fieldAttribute: null,
      fieldIncrement: false,
      asDefined: null,
    },
    fieldTypeList: [],
  }),
  validations: {
    fields: {
      fieldName: {
        required,
        maxLength: maxLength(64),
        alphaNumAndUnderscoreValidator,
      },
      fieldType: {
        required,
      },
      fieldLength: {
        required: requiredIf(function() {
          return this.lengthRequiredIf;
        }),
      },
      asDefined: {
        required: requiredIf(function() {
          return this.defaultRequiredIf;
        }),
      },
    },
    databaseData: {
      table: {
        modifiedName: {
          required,
          maxLength: maxLength(64),
          alphaNumAndUnderscoreValidator,
        },
      },
      fields: {
        currentFields: {
          required,
        },
      },
    },
    edit: {
      fieldName: {
        required,
        maxLength: maxLength(64),
        alphaNumAndUnderscoreValidator,
      },
      fieldType: {
        required,
      },
      fieldLength: {
        required: requiredIf(function() {
          return this.editLengthRequiredIf;
        }),
      },
      asDefined: {
        required: requiredIf(function() {
          return this.editDefaultRequiredIf;
        }),
      },
    },
  },
  computed: {
    fieldIndexList: {
      get() {
        return this.$databaseHelper.getMigrationIndexList();
      },
    },
    fieldDefaultList: {
      get() {
        return this.$databaseHelper.getMigrationDefaultList();
      },
    },
    fieldAttributeList: {
      get() {
        return this.$databaseHelper.getMigrationAttributeList();
      },
    },
    lengthRequiredIf: {
      get() {
        return (
          this.fields.fieldType == "double" ||
          this.fields.fieldType == "decimal" ||
          this.fields.fieldType == "float" ||
          this.fields.fieldType == "varchar" ||
          this.fields.fieldType == "char" ||
          this.fields.fieldType == "set" ||
          this.fields.fieldType == "enum"
        );
      },
    },
    defaultRequiredIf: {
      get() {
        return this.fields.fieldDefault == "as_defined";
      },
    },
    editLengthRequiredIf: {
      get() {
        return (
          this.edit.fieldType == "double" ||
          this.edit.fieldType == "decimal" ||
          this.edit.fieldType == "float" ||
          this.edit.fieldType == "varchar" ||
          this.edit.fieldType == "char" ||
          this.edit.fieldType == "set" ||
          this.edit.fieldType == "enum"
        );
      },
    },
    editDefaultRequiredIf: {
      get() {
        return this.edit.fieldDefault == "as_defined";
      },
    },
  },
  mounted() {
    this.getInfoTable();
    this.getDbmsFieldType();
  },
  methods: {
    getDbmsFieldType() {
      this.$api.database
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

    getFieldLength(column) {
      if (
        column.type == "decimal" ||
        column.type == "double" ||
        column.type == "float"
      ) {
        return column.precision + "," + column.scale;
      } else if (column.type == "enum" || column.type == "set") {
        return column.options.join();
      } else if (column.type == "date") {
        return null;
      } else {
        return column.length;
      }
    },

    getFieldDefault(column) {
      if (column.default === "CURRENT_TIMESTAMP") {
        return "CURRENT_TIMESTAMP".toLowerCase();
      } else if (column.default === null || column.default === "null") {
        return null;
      } else {
        return "as_defined";
      }
    },

    getFieldAsDefined(column) {
      if (column.default === "CURRENT_TIMESTAMP") {
        return;
      } else if (column.default === null || column.default === "null") {
        return null;
      } else if (column.default === "") {
        return "";
      } else {
        return column.default;
      }
    },

    getInfoTable() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.database
        .read({
          table: this.$route.params.tableName,
        })
        .then((response) => {
          let data = response.data.columns;
          for (const [key, column] of Object.entries(data)) {
            this.databaseData.fields.currentFields.push({
              fieldName: column.name,
              fieldType: column.type,
              fieldLength: this.getFieldLength(column),
              fieldDefault: this.getFieldDefault(column),
              fieldNull: column.notnull ? false : true,
              fieldIndex:
                Object.keys(column.indexes).length > 0
                  ? Object.values(column.indexes)[0]
                      .type.toString()
                      .toLowerCase()
                  : null,
              fieldAttribute: column.unsigned == true ? "unsigned" : null,
              fieldIncrement: column.autoincrement,
              asDefined: this.getFieldAsDefined(column),
            });
          }

          this.databaseData.table.currentName = this.$route.params.tableName;
          this.databaseData.table.modifiedName = this.$route.params.tableName;
          this.databaseData.timestamp = response.data.timestamp;
          this.$vs.loading.close();
        })
        .catch((error) => {
          this.$vs.loading.close();
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
        this.$vs.loading(this.$loadingConfig);

        this.$api.database
          .edit(this.databaseData)
          .then((response) => {
            this.$vs.loading.close();
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
            this.errors = error.error;
            this.$vs.loading.close();
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          });
      }
    },

    setField() {
      this.$v.fields.$touch();
      if (!this.$v.fields.$invalid) {
        this.databaseData.fields.modifiedFields.push({
          modifyType: "CREATE",
          ...this.fields,
        });
        this.databaseData.fields.currentFields.push({
          ...this.fields,
        });
        this.clearInput();
      }
    },

    clearInput() {
      this.fields.fieldName = "";
      this.fields.fieldType = "";
      this.fields.fieldLength = null;
      this.fields.fieldDefault = null;
      this.fields.asDefined = null;
      this.fields.fieldNull = false;
      this.fields.fieldIndex = null;
      this.fields.fieldAttribute = null;
      this.fields.fieldIncrement = false;
    },

    editField(item, index) {
      this.editDialog = true;

      this.edit = { ...item };

      this.willEdit = index;
    },

    saveEdit() {
      this.$v.edit.$touch();
      if (!this.$v.edit.$invalid) {
        let isFieldModified = 0;

        for (const [index, field] of Object.entries(
          Object.entries(this.databaseData.fields.currentFields[this.willEdit])
        )) {
          if (field[1] !== this.edit[field[0]]) {
            isFieldModified++;
          }
        }

        if (isFieldModified > 0) {
          if (
            this.databaseData.fields.currentFields[this.willEdit].fieldName !==
            this.edit.fieldName
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "RENAME",
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldName,
              new: this.edit.fieldName,
            });
          }

          let row = {
            fieldName: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldName,
              new: this.edit.fieldName,
            },
            fieldType: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldType,
              new: this.edit.fieldType,
            },
            fieldLength: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldLength,
              new: this.edit.fieldLength,
            },
            fieldIndex: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldIndex,
              new: this.edit.fieldIndex,
            },
            fieldDefault: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldDefault,
              new: this.edit.fieldDefault,
            },
            asDefined: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .asDefined,
              new: this.edit.asDefined,
            },
            fieldNull: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldNull,
              new: this.edit.fieldNull,
            },
            fieldIndex: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldIndex,
              new: this.edit.fieldIndex,
            },
            fieldIncrement: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldIncrement,
              new: this.edit.fieldIncrement,
            },
            fieldAttribute: {
              current: this.databaseData.fields.currentFields[this.willEdit]
                .fieldAttribute,
              new: this.edit.fieldAttribute,
            },
          };

          if (
            this.databaseData.fields.currentFields[this.willEdit].fieldType !==
            this.edit.fieldType
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_TYPE",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit]
              .fieldLength !== this.edit.fieldLength
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_LENGTH",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit]
              .fieldDefault !== this.edit.fieldDefault
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_DEFAULT",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit].asDefined !==
            this.edit.asDefined
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_DEFINED",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit].fieldNull !==
            this.edit.fieldNull
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_NULL",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit].fieldIndex !==
            this.edit.fieldIndex
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_INDEX",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit]
              .fieldAttribute !== this.edit.fieldAttribute
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_ATTRIBUTE",
              ...row,
            });
          }

          if (
            this.databaseData.fields.currentFields[this.willEdit]
              .fieldIncrement !== this.edit.fieldIncrement
          ) {
            this.databaseData.fields.modifiedFields.push({
              modifyType: "UPDATE_INCREMENT",
              ...row,
            });
          }
        }

        this.editDialog = false;

        this.databaseData.fields.currentFields[this.willEdit] = {
          ...this.edit,
        };
      }
    },

    dropField(item, index) {
      this.deleteDialog = true;
      (this.deletedItem = item), (this.willDelete = index);
    },

    saveDrop() {
      this.databaseData.fields.modifiedFields.push({
        modifyType: "DROP_FIELD",
        field: this.deletedItem,
      });

      this.databaseData.fields.currentFields.splice(this.willDelete, 1);
    },
  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}
.vs-select--options {
  z-index: 99999 !important;
}
</style>
