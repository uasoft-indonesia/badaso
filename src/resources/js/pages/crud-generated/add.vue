<template>
  <div>
    <template v-if="!isMaintenance">
      <badaso-breadcrumb-row full> </badaso-breadcrumb-row>
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
              <vs-col vs-lg="12" v-if="!isValid">
                <p class="is-error">No fields have been filled</p>
              </vs-col>
              <vs-col
                v-for="(dataRow, rowIndex) in dataType.dataRows"
                :key="rowIndex"
                :vs-lg="dataRow.details.size ? dataRow.details.size : '12'"
              >
                <!-- <input type="text" v-model="dataRow.value"> -->
                <!-- <vs-input type="text" v-model="dataRow.value"></vs-input> -->
                <template v-if="dataRow.add == 1">
                  <badaso-text
                    v-if="dataRow.type == 'text'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-text>
                  <badaso-email
                    v-if="dataRow.type == 'email'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-email>
                  <badaso-password
                    v-if="dataRow.type == 'password'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-password>
                  <badaso-textarea
                    v-if="dataRow.type == 'textarea'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-textarea>
                  <badaso-search
                    v-if="dataRow.type == 'search'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-search>
                  <badaso-number
                    v-if="dataRow.type == 'number'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-number>
                  <badaso-url
                    v-if="dataRow.type == 'url'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-url>
                  <badaso-time
                    v-if="dataRow.type == 'time'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-time>
                  <badaso-date
                    v-if="dataRow.type == 'date'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-date>
                  <badaso-datetime
                    v-if="dataRow.type == 'datetime'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-datetime>
                  <badaso-upload-image
                    v-if="dataRow.type == 'upload_image'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-image>
                  <badaso-upload-image-multiple
                    v-if="dataRow.type == 'upload_image_multiple'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-image-multiple>
                  <badaso-upload-file
                    v-if="dataRow.type == 'upload_file'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-file>
                  <badaso-upload-file-multiple
                    v-if="dataRow.type == 'upload_file_multiple'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-file-multiple>
                  <badaso-switch
                    v-if="dataRow.type == 'switch'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-switch>
                  <badaso-slider
                    v-if="dataRow.type == 'slider'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-slider>
                  <badaso-editor
                    v-if="dataRow.type == 'editor'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-editor>
                  <badaso-tags
                    v-if="dataRow.type == 'tags'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-tags>
                  <badaso-color-picker
                    v-if="dataRow.type == 'color_picker'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-color-picker>
                  <badaso-hidden
                    v-if="dataRow.type == 'hidden'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-hidden>
                  <badaso-checkbox
                    v-if="dataRow.type == 'checkbox'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  ></badaso-checkbox>
                  <badaso-select
                    v-if="dataRow.type == 'select'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  ></badaso-select>
                  <badaso-select-multiple
                    v-if="dataRow.type == 'select_multiple'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  ></badaso-select-multiple>
                  <badaso-radio
                    v-if="dataRow.type == 'radio'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                    :items="dataRow.details.items ? dataRow.details.items : []"
                  ></badaso-radio>
                  <badaso-code-editor
                    v-if="dataRow.type == 'code'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-code-editor>
                  <badaso-select
                    v-if="
                      dataRow.type == 'relation' &&
                      dataRow.relation.relationType == 'belongs_to'
                    "
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
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
                  ></badaso-select>
                  <badaso-text
                    v-if="
                      dataRow.type == 'relation' &&
                      dataRow.relation.relationType !== 'belongs_to'
                    "
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-text>
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
                  <vs-icon icon="save"></vs-icon>
                  {{ $t("crudGenerated.add.button") }}
                </vs-button>
                <vs-button
                  :to="{
                    name: 'DataPendingAddBrowse',
                    params: {
                      urlBase64: base64PathName,
                    },
                  }"
                  v-if="dataLength > 0 && !isOnline"
                  color="success"
                  type="relief"
                >
                  <vs-icon icon="history"></vs-icon>
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
      <badaso-breadcrumb-row full> </badaso-breadcrumb-row>

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
  }),
  mounted() {
    this.getDataType();
    this.getRelationDataBySlug();
    this.requestObjectStoreData();
  },
  methods: {
    submitForm() {
      this.errors = {};
      this.isValid = true;

      // init data rows
      const dataRows = {};
      for (const row of this.dataType.dataRows) {
        if (row && row.value) {
          dataRows[row.field] = row.value;
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
            data.value = false;
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
        console.log(response.data.crudData.dataRows);
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
  },
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
};
</script>
