<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("database.add.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="databaseData.table"
              size="6"
              :label="$t('database.add.field.table')"
              :placeholder="$t('database.add.field.table')"
              required
            >
            </badaso-text>
          </vs-row>
          <div v-if="$v.databaseData.table.$dirty">
            <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.databaseData.table.required">
              {{ $t('database.add.row.field.tableName') }}
            </i18n>
          </div>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("database.add.row.title") }}</h3>
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
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldDefault") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.asDefined") }}
                  </th>
                  <th style="min-width: 100px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldNull") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldIndex") }}
                  </th>
                  <th style="min-width: 200px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldAttribute") }}
                  </th>
                  <th style="min-width: 100px; word-wrap: nowrap">
                    {{ $t("database.add.row.field.fieldIncrement") }}
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
                      {{ item.fieldDefault }}
                    </td>
                    <td>
                      {{ item.asDefined }}
                    </td>
                    <td>
                      {{ item.fieldNull }}
                    </td>
                    <td>
                      {{ item.fieldIndex }}
                    </td>
                    <td>
                      {{ item.fieldAttribute }}
                    </td>
                    <td>
                      {{ item.fieldIncrement }}
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

                    <div v-if="$v.fields.fieldName.$dirty">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldName.required">
                        {{ $t('database.add.row.field.fieldName') }}
                      </i18n>
                    </div>
                  </td>
                  <td>
                    <vs-select class="selectExample mb-2" v-model="fields.fieldType">
                      <vs-select-item
                        :key="index"
                        :value="item.value"
                        :text="item.label"
                        v-for="(item, index) in fieldTypeList"
                      />
                    </vs-select>

                    <div v-if="$v.fields.fieldType.$dirty">
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

                    <div v-if="$v.fields.fieldLength.$dirty">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.fieldLength.required">
                        {{ $t('database.add.row.field.fieldLength') }}
                      </i18n>
                    </div>
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

                    <div v-if="$v.fields.asDefined.$dirty">
                      <i18n path="vuelidate.required" style="color: rgba(var(--vs-danger),1)" v-if="!$v.fields.asDefined.required">
                        {{ $t('database.add.row.field.fieldDefault') }}
                      </i18n>
                    </div>
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
                    <vs-checkbox
                      v-model="fields.fieldIncrement"
                      class="mb-1"
                      style="justify-content: start"
                    ></vs-checkbox>
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
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm()">
                <vs-icon icon="save"></vs-icon> {{ $t("database.add.button") }}
              </vs-button>
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
import draggable from "vuedraggable";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoText from "../../components/BadasoText";
import BadasoSwitch from "../../components/BadasoSwitch";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoHidden from "../../components/BadasoHidden.vue";
import { required, requiredIf } from "vuelidate/lib/validators";

export default {
  name: "Browse",
  components: {
    draggable,
    BadasoBreadcrumb,
    BadasoText,
    BadasoSwitch,
    BadasoSelect,
    BadasoHidden,
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
      rows: [],
    },
  }),
  validations: {
    fields: {
      fieldName: {
        required,
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
      },
      rows: {
        required,
      },
    },
  },
  computed: {
    fieldTypeList: {
      get() {
        return this.$helper.getMigrationTypeList();
      },
    },
    fieldIndexList: {
      get() {
        return this.$helper.getMigrationIndexList();
      },
    },
    fieldDefaultList: {
      get() {
        return this.$helper.getMigrationDefaultList();
      },
    },
    fieldAttributeList: {
      get() {
        return this.$helper.getMigrationAttributeList();
      },
    },
  },
  methods: {
    submitForm() {
      this.$v.databaseData.$touch();
      if (!this.$v.databaseData.$invalid) {
        this.$vs.loading({
          type: "sound",
        });
        this.$api.database
          .add(this.databaseData)
          .then((response) => {
            this.$vs.loading.close();
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: response.message,
              color: "success",
            });
            this.$store.commit("FETCH_MENU");
            this.$store.commit("FETCH_USER");
            this.$router.push({ name: "DatabaseBrowse" });
          })
          .catch((error) => {
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
        this.databaseData.rows.push({
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
  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}
</style>
