<template>
  <div>
    <template v-if="!isMaintenance">
      <badaso-breadcrumb-row full />
      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('add', dataType)">
        <vs-col vs-lg="12">
          <vs-card>
            <div slot="header">
              <h3>
                {{
                  $t("crudGenerated.add.title", {
                    tableName: dataType.displayNameSingular,
                  })
                }}
              </h3>
            </div>
            <vs-row>
              <vs-col v-if="!isValid" vs-lg="12">
                <p class="is-error">No fields have been filled</p>
              </vs-col>
              <vs-col
                v-for="(dataRow, rowIndex) in dataType.dataRows"
                :key="rowIndex"
                :vs-lg="dataRow.details.size ? dataRow.details.size : '12'"
              >
                <template v-if="dataRow.add == 1">
                  <!-- <input type="text" v-model="dataRow.value"> -->
                  <!-- <vs-input type="text" v-model="dataRow.value"></vs-input> -->
                  <badaso-text
                    v-if="dataRow.type == 'text'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-email
                    v-if="dataRow.type == 'email'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-password
                    v-if="dataRow.type == 'password'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-textarea
                    v-if="dataRow.type == 'textarea'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-search
                    v-if="dataRow.type == 'search'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-number
                    v-if="dataRow.type == 'number'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-url
                    v-if="dataRow.type == 'url'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-time
                    v-if="dataRow.type == 'time'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    value-zone="local"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-date
                    v-if="dataRow.type == 'date'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-datetime
                    v-if="dataRow.type == 'datetime'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    value-zone="local"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-upload-image
                    v-if="dataRow.type == 'upload_image'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-upload-image-multiple
                    v-if="dataRow.type == 'upload_image_multiple'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-upload-file
                    v-if="dataRow.type == 'upload_file'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-upload-file-multiple
                    v-if="dataRow.type == 'upload_file_multiple'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-switch
                    v-if="dataRow.type == 'switch'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-slider
                    v-if="dataRow.type == 'slider'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-editor
                    v-if="dataRow.type == 'editor'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-tags
                    v-if="dataRow.type == 'tags'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-color-picker
                    v-if="dataRow.type == 'color_picker'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-hidden
                    v-if="
                      dataRow.type == 'hidden' ||
                      dataRow.type == 'data_identifier' ||
                      dataRow.type == 'relation'
                    "
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-checkbox
                    v-if="dataRow.type == 'checkbox'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  />
                  <badaso-select
                    v-if="dataRow.type == 'select'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  />
                  <badaso-select-multiple
                    v-if="dataRow.type == 'select_multiple'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  />
                  <badaso-radio
                    v-if="dataRow.type == 'radio'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  />
                  <badaso-code-editor
                    v-if="dataRow.type == 'code'"
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-select
                    v-if="
                      dataRow.type == 'relation' &&
                      dataRow.relation.relationType == 'belongs_to'
                    "
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :items="
                      relationData[
                        $caseConvert.stringSnakeToCamel(
                          dataRow.relation.destinationTable
                        )
                      ]
                    "
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  />
                  <badaso-select-multiple
                    v-if="
                      dataRow.type == 'relation' &&
                      dataRow.relation.relationType == 'belongs_to_many'
                    "
                    v-model="dataRow.value"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="
                      relationData[
                        $caseConvert.stringSnakeToCamel(
                          dataRow.relation.destinationTable
                        )
                      ]
                    "
                  />
                </template>
              </vs-col>
            </vs-row>
          </vs-card>
        </vs-col>
        <vs-col vs-lg="12">
          <vs-card class="action-card">
            <vs-row>
              <vs-col vs-lg="12">
                <vs-button color="primary" type="relief" @click="submitForm">
                  <vs-icon icon="save" />
                  {{ $t("crudGenerated.add.button") }}
                </vs-button>
                <vs-button
                  v-if="dataLength > 0 && !isOnline"
                  :to="{
                    name: 'DataPendingAddBrowse',
                    params: {
                      urlBase64: base64PathName,
                    },
                  }"
                  color="success"
                  type="relief"
                >
                  <vs-icon icon="history" />
                  <strong
                    >{{ dataLength }} {{ $t("offlineFeature.dataPending") }}
                  </strong>
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
                <h3>
                  {{
                    $t("crudGenerated.warning.notAllowedToAdd", {
                      tableName: dataType.displayNameSingular,
                    })
                  }}
                </h3>
              </vs-col>
            </vs-row>
          </vs-card>
        </vs-col>
      </vs-row>
    </template>
    <template v-if="isMaintenance">
      <badaso-breadcrumb-row full />

      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('add', dataType)">
        <vs-col vs-lg="12">
          <div class="badaso-maintenance__container">
            <img :src="`${maintenanceImg}`" alt="Maintenance Icon" />
            <h1 class="badaso-maintenance__text">
              We are under <br />maintenance
            </h1>
          </div>
        </vs-col>
      </vs-row>
    </template>
  </div>
</template>

<script>
export default {
  name: "CrudGeneratedAdd",
  components: {},
  data: () => ({
    isValid: true,
    errors: {},
    dataType: {},
    relationData: {},
    isMaintenance: false,
    dataLength: 0,
    pathname: location.pathname,
    userId: "",
  }),
  computed: {
    isOnline: {
      get() {
        const isOnline = this.$store.getters["badaso/getGlobalState"].isOnline;
        return isOnline;
      },
    },
    base64PathName() {
      return window.btoa(location.pathname);
    },
    maintenanceImg() {
      const config = this.$store.getters["badaso/getConfig"];
      return config.maintenanceImage;
    },
  },
  mounted() {
    this.getDataType();
    this.getRelationDataBySlug();
    this.requestObjectStoreData();
    this.getUser();
  },
  methods: {
    submitForm() {
      this.errors = {};
      this.isValid = true;

      // init data rows
      const dataRows = {};
      for (const row of this.dataType.dataRows) {
        if (
          (row && row.value) ||
          row.type == "switch" ||
          row.type == "slider"
        ) {
          dataRows[row.field] = row.value;
        }
        if (row.type == "data_identifier") {
          dataRows[row.field] = this.userId;
        }
      }

      // validate values in data rows must not equals 0
      if (Object.values(dataRows).length == 0) {
        this.isValid = false;
        return;
      }

      // start request
      this.$openLoader();
      this.$api.badasoEntity
        .add({
          slug: this.$route.params.slug,
          data: dataRows,
        })
        .then((response) => {
          this.$closeLoader();
          this.$router.push({
            name: "CrudGeneratedBrowse",
            params: {
              slug: this.$route.params.slug,
            },
          });
        })
        .catch((error) => {
          this.requestObjectStoreData();
          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    async getDataType() {
      this.$openLoader();

      try {
        const response = await this.$api.badasoCrud.readBySlug({
          slug: this.$route.params.slug,
        });

        this.$closeLoader();
        this.dataType = response.data.crudData;
        const dataRows = response.data.crudData.dataRows.map((data) => {
          if (
            data.value == undefined &&
            (data.type == "upload_image" || data.type == "upload_file")
          ) {
            data.value = "";
          } else if (
            data.value == undefined &&
            (data.type == "upload_image_multiple" ||
              data.type == "upload_file_multiple" ||
              data.type == "select_multiple" ||
              data.type == "checkbox")
          ) {
            data.value = Array;
          } else if (data.value == undefined && data.type == "slider") {
            data.value = 0;
          } else if (data.value == undefined && data.type == "switch") {
            data.value = 0;
          } else if (data.value == undefined && data.type == "tags") {
            data.value = "";
          } else if (data.value == undefined) {
            data.value = "";
          }
          try {
            data.details = JSON.parse(data.details);
            if (data.type == "hidden") {
              data.value = data.details.value ? data.details.value : "";
            }
          } catch (error) {}
          return data;
        });
        this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows));
      } catch (error) {
        if (error.status == 503) {
          this.isMaintenance = true;
        }
        this.$closeLoader();
        this.$vs.notify({
          title: this.$t("alert.danger"),
          text: error.message,
          color: "danger",
        });
      }
    },
    getRelationDataBySlug() {
      this.$openLoader();
      this.$api.badasoTable
        .relationDataBySlug({
          slug: this.$route.params.slug,
        })
        .then((response) => {
          this.$closeLoader();
          this.relationData = response.data;
        })
        .catch((error) => {
          if (error.status == 503) {
            this.isMaintenance = true;
          }
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    requestObjectStoreData() {
      this.$readObjectStore(this.pathname).then((store) => {
        if (store.result) {
          this.dataLength = store.result.data.length;
        }
      });
    },
    getUser() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuthUser
        .user({})
        .then((response) => {
          this.$closeLoader();
          this.userId = response.data.user.id;
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
