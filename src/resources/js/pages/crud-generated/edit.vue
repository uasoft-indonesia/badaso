<template>
  <div>
    <template v-if="!isMaintenance">
      <badaso-breadcrumb-row full> </badaso-breadcrumb-row>
      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('edit', dataType)">
        <vs-col vs-lg="12">
          <vs-card>
            <div slot="header">
              <h3>
                {{
                  $t("crudGenerated.edit.title", {
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
                <template v-if="dataRow.edit && dataRow.type !== 'hidden'">
                  <!-- <input type="text" v-model="dataRow.value"> -->
                  <!-- <vs-input type="text" v-model="dataRow.value"></vs-input> -->
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
                    size="12"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-image>
                  <badaso-upload-image-multiple
                    v-if="dataRow.type == 'upload_image_multiple'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-image-multiple>
                  <badaso-upload-file
                    v-if="dataRow.type == 'upload_file'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
                    :alert="
                      errors[$caseConvert.stringSnakeToCamel(dataRow.field)]
                    "
                  ></badaso-upload-file>
                  <badaso-upload-file-multiple
                    v-if="dataRow.type == 'upload_file_multiple'"
                    :label="dataRow.displayName"
                    :placeholder="dataRow.displayName"
                    v-model="dataRow.value"
                    size="12"
                    :private-only="dataRow.details.type == 'private-only'"
                    :shares-only="dataRow.details.type == 'shares-only'"
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
                  {{ $t("crudGenerated.edit.button") }}
                </vs-button>
                <vs-button
                  :to="{
                    name: 'DataPendingEditRead',
                    params: {
                      urlBase64: base64PathName,
                    },
                  }"
                  v-if="dataLength > 0 && !isOnline"
                  color="success"
                  type="relief"
                >
                  <vs-icon icon="history"></vs-icon>
                  <strong>{{ $t("offlineFeature.dataUpdatePending") }}</strong>
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
                    $t("crudGenerated.warning.notAllowedToEdit", {
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

      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('edit', dataType)">
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
// eslint-disable-next-line no-unused-vars
import * as _ from "lodash";

export default {
  name: "CrudGeneratedEdit",
  components: {},
  data: () => ({
    isValid: true,
    errors: {},
    dataType: {},
    relationData: {},
    dataLength: 0,
    pathname: location.pathname,
    isMaintenance: false,
  }),
  mounted() {
    this.getDetailEntity();
    this.getRelationDataBySlug();
    this.requestObjectStoreData();
  },
  methods: {
    submitForm() {
      // init data row
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

      this.$openLoader();
      this.$api.badasoEntity
        .edit({
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
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    async getDetailEntity() {
      this.$openLoader();

      try {
        const response = await this.$api.badasoEntity.read({
          slug: this.$route.params.slug,
          id: this.$route.params.id,
        });

        const {
          data: { dataType },
        } = await this.$api.badasoTable.getDataType({
          slug: this.$route.params.slug,
        });

        this.$closeLoader();

        this.dataType = dataType;
        this.record = response.data;
        const dataRows = this.dataType.dataRows.map((data) => {
          try {
            data.add = data.add == 1;
            data.edit = data.edit == 1;
            data.read = data.read == 1;
            data.details = JSON.parse(data.details);

            if (
              data.type == "upload_image_multiple" ||
              data.type == "upload_file_multiple" ||
              data.type == "checkbox" ||
              data.type == "select_multiple"
            ) {
              const val =
                this.record[this.$caseConvert.stringSnakeToCamel(data.field)];
              if (val) {
                data.value = val.split(",");
              }
            } else if (data.type == "switch") {
              data.value = this.record[
                this.$caseConvert.stringSnakeToCamel(data.field)
              ]
                ? this.record[this.$caseConvert.stringSnakeToCamel(data.field)]
                : false;
            } else if (data.type == "slider") {
              data.value = parseInt(
                this.record[this.$caseConvert.stringSnakeToCamel(data.field)]
              );
            } else if (data.type == "datetime") {
              data.value = this.record[
                this.$caseConvert.stringSnakeToCamel(data.field)
              ]
                ? this.record[
                    this.$caseConvert.stringSnakeToCamel(data.field)
                  ].replace(" ", "T")
                : null;
            } else if (data.value == undefined && data.type == "hidden") {
              data.value = data.details.value ? data.details.value : "";
            } else if (
              data.type == "text" ||
              data.type == "hidden" ||
              data.type == "url" ||
              data.type == "search" ||
              data.type == "password"
            ) {
              data.value = this.record[
                this.$caseConvert.stringSnakeToCamel(data.field)
              ]
                ? this.record[this.$caseConvert.stringSnakeToCamel(data.field)]
                : "";
            } else {
              data.value =
                this.record[this.$caseConvert.stringSnakeToCamel(data.field)];
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
