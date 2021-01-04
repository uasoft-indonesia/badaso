<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb full></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add</h3>
          </div>
          <vs-row>
            <vs-col
              v-for="(dataRow, rowIndex) in dataType.dataRows"
              :key="rowIndex"
              :vs-lg="dataRow.details.size ? dataRow.details.size : '12'"
              v-if="dataRow.add === 1 && dataRow.type !== 'hidden'"
            >
              <!-- <input type="text" v-model="dataRow.value"> -->
              <!-- <vs-input type="text" v-model="dataRow.value"></vs-input> -->
              <badaso-text
                v-if="dataRow.type === 'text'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-text>
              <badaso-password
                v-if="dataRow.type === 'password'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-password>
              <badaso-textarea
                v-if="dataRow.type === 'textarea'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-textarea>
              <badaso-search
                v-if="dataRow.type === 'search'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-search>
              <badaso-number
                v-if="dataRow.type === 'number'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-number>
              <badaso-url
                v-if="dataRow.type === 'url'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-url>
              <badaso-time
                v-if="dataRow.type === 'time'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-time>
              <badaso-date
                v-if="dataRow.type === 'date'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-date>
              <badaso-datetime
                v-if="dataRow.type === 'datetime'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-datetime>
              <badaso-upload-image
                v-if="dataRow.type === 'upload_image'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-upload-image>
              <badaso-upload-image-multiple
                v-if="dataRow.type === 'upload_image_multiple'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-upload-image-multiple>
              <badaso-upload-file
                v-if="dataRow.type === 'upload_file'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-upload-file>
              <badaso-upload-file-multiple
                v-if="dataRow.type === 'upload_file_multiple'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-upload-file-multiple>
              <badaso-switch
                v-if="dataRow.type === 'switch'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-switch>
              <badaso-slider
                v-if="dataRow.type === 'slider'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-slider>
              <badaso-editor
                v-if="dataRow.type === 'editor'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-editor>
              <badaso-tags
                v-if="dataRow.type === 'tags'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-tags>
              <badaso-color-picker
                v-if="dataRow.type === 'color_picker'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-color-picker>
              <badaso-hidden
                v-if="dataRow.type === 'hidden'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
              ></badaso-hidden>
              <badaso-checkbox
                v-if="dataRow.type === 'checkbox'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
                :items="dataRow.details.items ? dataRow.details.items : []"
              ></badaso-checkbox>
              <badaso-select
                v-if="dataRow.type === 'select'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
                :items="dataRow.details.items ? dataRow.details.items : []"
              ></badaso-select>
              <badaso-select-multiple
                v-if="dataRow.type === 'select_multiple'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
                :items="dataRow.details.items ? dataRow.details.items : []"
              ></badaso-select-multiple>
              <badaso-radio
                v-if="dataRow.type === 'radio'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
                :items="dataRow.details.items ? dataRow.details.items : []"
              ></badaso-radio>
              <badaso-code-editor
                v-if="dataRow.type === 'code'"
                :label="dataRow.displayName"
                :placeholder="dataRow.displayName"
                v-model="dataRow.value"
                size="12"
              ></badaso-code-editor>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Save
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import * as _ from "lodash";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoText from "../../components/BadasoText";
import BadasoPassword from "../../components/BadasoPassword";
import BadasoTextarea from "../../components/BadasoTextarea";
import BadasoCheckbox from "../../components/BadasoCheckbox";
import BadasoSearch from "../../components/BadasoSearch";
import BadasoNumber from "../../components/BadasoNumber";
import BadasoUrl from "../../components/BadasoUrl";
import BadasoTime from "../../components/BadasoTime";
import BadasoDate from "../../components/BadasoDate";
import BadasoDatetime from "../../components/BadasoDatetime";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoSelectMultiple from "../../components/BadasoSelectMultiple";
import BadasoRadio from "../../components/BadasoRadio";
import BadasoSwitch from "../../components/BadasoSwitch";
import BadasoSlider from "../../components/BadasoSlider";
import BadasoEditor from "../../components/BadasoEditor";
import BadasoTags from "../../components/BadasoTags";
import BadasoColorPicker from "../../components/BadasoColorPicker";
import BadasoUploadImage from "../../components/BadasoUploadImage";
import BadasoUploadImageMultiple from "../../components/BadasoUploadImageMultiple";
import BadasoUploadFile from "../../components/BadasoUploadFile";
import BadasoUploadFileMultiple from "../../components/BadasoUploadFileMultiple";
import BadasoHidden from "../../components/BadasoHidden";
import BadasoCodeEditor from "../../components/BadasoCodeEditor";

export default {
  name: "Browse",
  components: {
    BadasoBreadcrumb,
    BadasoText,
    BadasoPassword,
    BadasoTextarea,
    BadasoCheckbox,
    BadasoSearch,
    BadasoNumber,
    BadasoUrl,
    BadasoTime,
    BadasoDate,
    BadasoDatetime,
    BadasoSelect,
    BadasoSelectMultiple,
    BadasoRadio,
    BadasoSwitch,
    BadasoSlider,
    BadasoEditor,
    BadasoTags,
    BadasoColorPicker,
    BadasoUploadImage,
    BadasoUploadImageMultiple,
    BadasoUploadFile,
    BadasoUploadFileMultiple,
    BadasoHidden,
    BadasoCodeEditor,
  },
  data: () => ({
    dataType: {},
  }),
  mounted() {
    this.getDetailEntity();
  },
  methods: {
    submitForm() {
      let dataRows = this.dataType.dataRows.filter(function(row) {
        return row && row.value;
      });
      dataRows = dataRows.map((row) => {
        return {
          field: row.field,
          value: row.value,
        };
      });

      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .edit({
          slug: this.$route.params.slug,
          data: dataRows,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({
            name: "EntityBrowse",
            params: {
              slug: this.$route.params.slug,
            },
          });
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    getDetailEntity() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .read({
          slug: this.$route.params.slug,
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.dataType = response.data.dataType;
          this.record = response.data.detail;
          console.log(this.record);
          let dataRows = this.dataType.dataRows.map((data) => {
            try {
              data.details = JSON.parse(data.details);

              if (
                data.type === "upload_image_multiple" ||
                data.type === "upload_file_multiple" ||
                data.type === "checkbox" ||
                data.type === "select_multiple"
              ) {
                let val = this.record[
                  this.$caseConvert.stringSnakeToCamel(data.field)
                ];
                if (val) {
                  data.value = val.split(",");
                }
              } else if (data.type === "switch") {
                data.value = this.record[
                  this.$caseConvert.stringSnakeToCamel(data.field)
                ]
                  ? this.record[
                      this.$caseConvert.stringSnakeToCamel(data.field)
                    ]
                  : false;
              } else if (data.type === "slider") {
                data.value = parseInt(
                  this.record[this.$caseConvert.stringSnakeToCamel(data.field)]
                );
              } else if (data.type === "datetime") {
                data.value = this.record[
                  this.$caseConvert.stringSnakeToCamel(data.field)
                ]
                  ? this.record[
                      this.$caseConvert.stringSnakeToCamel(data.field)
                    ].replace(" ", "T")
                  : null;
              } else if (
                data.type === "text" ||
                data.type === "hidden" ||
                data.type === "url" ||
                data.type === "search" ||
                data.type === "password"
              ) {
                data.value = this.record[
                  this.$caseConvert.stringSnakeToCamel(data.field)
                ]
                  ? this.record[
                      this.$caseConvert.stringSnakeToCamel(data.field)
                    ]
                  : "";
              } else {
                data.value = this.record[
                  this.$caseConvert.stringSnakeToCamel(data.field)
                ];
              }
            } catch (error) {
              console.log(error);
            }
            console.log(data.displayName, data);
            return data;
          });
          this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows));
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
