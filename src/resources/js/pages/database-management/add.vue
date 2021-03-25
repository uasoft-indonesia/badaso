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
            <vs-col vs-lg="6" vs-align="center">
              <badaso-text
                v-model="databaseData.table"
                size="12"
                :label="$t('database.add.field.table')"
                :placeholder="$t('database.add.field.table')"
                required
              >
              </badaso-text>
            </vs-col>
            <vs-col vs-align="center" vs-lg="3">
              <vs-checkbox v-model="databaseData.timestamp">{{ $t('database.add.row.field.timestamp') }}</vs-checkbox>
            </vs-col>
            <vs-col vs-lg="12">
              <div v-if="$v.databaseData.table.$dirty" class="d-inline-grid">
                <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.databaseData.table.required">
                  {{ $t('database.add.row.field.tableName') }}
                </i18n>
                <i18n path="vuelidate.alphaNumAndUnderscoreValidator" style="color: rgba(var(--vs-danger),1)" v-if="!$v.databaseData.table.alphaNumAndUnderscoreValidator">
                  {{ $t('database.add.row.field.tableName') }}
                </i18n>
                <i18n path="vuelidate.maxLength" style="color: rgba(var(--vs-danger),1)" v-if="!$v.databaseData.table.maxLength">
                  <template v-slot:field>
                    {{ $t('database.add.row.field.tableName') }}
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
            <span style="color: rgba(var(--vs-danger),1)">
              <i18n path="database.warning.docs">
                <a target="_blank" href="https://badaso-docs.uatech.co.id/docs/en/core-concept/database-management/">docs</a>
              </i18n>
            </span>
          </div>
          <vs-row>
            <vs-col col-lg="12" style="overflow-x: auto">
              <table class="table">
                <thead>
                  <th style="word-wrap: nowrap"></th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldName") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldType") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldLength") }}
                  </th>
                  <th style="min-width: 100px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldIncrement") }}
                  </th>
                  <th style="min-width: 100px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldNull") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldDefault") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.asDefined") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldIndex") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldAttribute") }}
                  </th>
                  <th style="min-width: 150px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.action") }}
                  </th>
                </thead>
                <draggable v-model="databaseData.rows" tag="tbody">
                  <tr :key="index" v-for="(item, index) in databaseData.rows">
                    <td>
                      <vs-icon
                        icon="drag_indicator"
                        class="drag_icon"
                      ></vs-icon>
                    </td>
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
                      <vs-button color="danger" type="relief" @click="dropField(item, index)">
                        <vs-icon icon="delete"></vs-icon>
                      </vs-button>
                    </td>
                  </tr>
                </draggable>
                <tr>
                  <td colspan="2">
                    <vs-input
                      class="inputx mb-2"
                      :placeholder="$t('database.add.row.field.fieldName')"
                      v-model="fields.fieldName"
                    />

                    <div v-if="$v.fields.fieldName.$dirty" class="d-inline-grid">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldName.required">
                        {{ $t('database.add.row.field.fieldName') }}
                      </i18n>
                      <i18n path="vuelidate.alphaNumAndUnderscoreValidator" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldName.alphaNumAndUnderscoreValidator">
                        {{ $t('database.add.row.field.fieldName') }}
                      </i18n>
                      <i18n path="vuelidate.maxLength" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldName.maxLength">
                        <template v-slot:field>
                          {{ $t('database.add.row.field.fieldName') }}
                        </template>
                        <template v-slot:length>
                          {{ $v.fields.fieldName.$params.maxLength.max }}
                        </template>
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-select class="selectExample mb-2" v-model="fields.fieldType">
                      <div :key="index" v-for="item, index in fieldTypeList">
                        <vs-select-group :title="item.title" v-if="item.group">
                          <vs-select-item :key="index" :value="item.value" :text="item.label" v-for="item,index in item.group"/>
                        </vs-select-group>
                      </div>
                    </vs-select>

                    <div v-if="$v.fields.fieldType.$dirty" class="d-inline-grid">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldType.required">
                        {{ $t('database.add.row.field.fieldType') }}
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-input
                      class="inputx mb-2"
                      :placeholder="$t('database.add.row.field.fieldLength')"
                      v-model="fields.fieldLength"
                    />

                    <div v-if="$v.fields.fieldLength.$dirty" class="d-inline-grid">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldLength.required">
                        {{ $t('database.add.row.field.fieldLength') }}
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldIncrement"
                      class="mb-1"
                      style="justify-content: start"
                    ></vs-checkbox>
                  </td>
                  <td>
                    <vs-checkbox
                      v-model="fields.fieldNull"
                      class="mb-1"
                      style="justify-content: start"
                    ></vs-checkbox>
                  </td>
                  <td>
                    <vs-select
                      class="selectExample"
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
                      class="inputx mb-2"
                      :placeholder="$t('database.add.row.field.fieldDefault')"
                      v-model="fields.asDefined"
                    />

                    <div v-if="$v.fields.asDefined.$dirty" class="d-inline-grid">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.asDefined.required">
                        {{ $t('database.add.row.field.fieldDefault') }}
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-select
                      class="selectExample"
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
                      class="selectExample"
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
                      @click="setField()"
                    >
                      {{ $t("database.add.row.field.add") }}
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
                <vs-icon icon="save"></vs-icon> {{ $t("database.add.button") }}
              </vs-button>
            </vs-col>
            <vs-col vs-lg="10" vs-align="center" v-if="$v.databaseData.rows.$dirty" class="d-inline-grid">
              <i18n path="vuelidate.rowsRequired" style="color: rgba(var(--vs-danger),1)" v-if="!$v.databaseData.rows.required">
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
    <vs-prompt
      color="danger"
      @accept="saveDrop()"
      :active.sync="deleteDialog"
      title="Delete"
      :accept-text="$t('action.delete.accept')"
      :cancel-text="$t('action.delete.cancel')">
      <div class="con-exemple-prompt">
        {{ $t('database.edit.row.drop') }}
      </div>
    </vs-prompt>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import { required, requiredIf, maxLength, helpers } from "vuelidate/lib/validators";
const alphaNumAndUnderscoreValidator = helpers.regex('alphaNumAndDot', /^[a-zA-Z\d_]*$/i);

export default {
  name: "DatabaseManagementAdd",
  components: {
    draggable,
  },
  data: () => ({
    breadcrumb: [],
    fields: {
      fieldName: "",
      fieldType: "",
      fieldNull: false,
      fieldIncrement: false,
      fieldLength: null,
      fieldDefault: null,
      fieldIndex: null,
      fieldAttribute: null,
      asDefined: null,
    },
    databaseData: {
      table: "",
      timestamp: true,
      rows: [],
    },
    willDelete: null,
    deleteDialog: false,
    fieldTypeList: []
  }),
  validations: {
    fields: {
      fieldName: {
        required,
        maxLength: maxLength(64),
        alphaNumAndUnderscoreValidator
      },
      fieldType: {
        required,
      },
      fieldLength: {
        required: requiredIf(function () {
          return this.lengthRequiredIf;
        }),
      },
      asDefined: {
        required: requiredIf(function () {
          return this.defaultRequiredIf;
        }),
      },
    },
    databaseData: {
      table: {
        required,
        maxLength: maxLength(64),
        alphaNumAndUnderscoreValidator
      },
      rows: {
        required,
      },
    },
  },
  watch: {
    'fields.fieldIncrement': function (newVal) {
      if (newVal) {
        this.fields.fieldIndex = 'primary'
        if (this.fields.fieldType == "") {
          this.fields.fieldType = 'bigint'
        }
      } else {
        this.fields.fieldIndex = null
      }
    }
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
        return this.fields.fieldType == 'double' || this.fields.fieldType == 'decimal' || this.fields.fieldType == 'float' || this.fields.fieldType == 'varchar' || this.fields.fieldType == 'char' || this.fields.fieldType == 'set' || this.fields.fieldType == 'enum'
      }
    },
    defaultRequiredIf: {
      get() {
        return this.fields.fieldDefault == 'as_defined'
      }
    },
    editLengthRequiredIf: {
      get() {
        return this.edit.fieldType == 'double' || this.edit.fieldType == 'decimal' || this.edit.fieldType == 'float' || this.edit.fieldType == 'varchar' || this.edit.fieldType == 'char' || this.edit.fieldType == 'set' || this.edit.fieldType == 'enum'
      }
    },
    editDefaultRequiredIf: {
      get() {
        return this.edit.fieldDefault == 'as_defined'
      }
    },
  },
  mounted() {
    this.getDbmsFieldType()
  },
  methods: {
    getDbmsFieldType() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.database
        .getType()
        .then((response) => {
          this.$vs.loading.close();
          this.fieldTypeList = JSON.parse(response.data)
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
        this.$v.databaseData.$reset();
        this.$vs.loading(this.$loadingConfig);
        this.$api.database
          .add(this.databaseData)
          .then((response) => {
            this.$vs.loading.close();
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
            this.$vs.loading.close();

            if (error.errors.table) {
              message = error.errors.table[0]
            }

            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: message,
              color: "danger",
            });
          });
      }
    },
    setField() {
      this.$v.fields.$touch();
      if (!this.$v.fields.$invalid) {
        this.databaseData.rows.push({
          ...this.fields,
        });
        this.clearInput();
        this.$v.fields.$reset();
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

    dropField(item, index) {
      this.deleteDialog = true
      this.deletedItem = item,
      this.willDelete = index
    },

    saveDrop() {
      this.databaseData.rows.splice(this.willDelete, 1)
    }
  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}
</style>
