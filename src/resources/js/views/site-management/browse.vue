<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" :to="{ name: 'SiteAdd' }"
            v-if="$helper.isAllowed('add_configurations')"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('browse_configurations')">
      <vs-col vs-lg="12">
        <vs-card>
          <vs-tabs>
            <vs-tab
              v-for="(group, index) in groupList"
              :key="index"
              :label="group.label"
            >
              <vs-row
                style="padding-top: 20px;"
                v-for="(config, index) in filterConfigurations(group.value)"
                :key="index"
              >
                <badaso-text
                  v-if="config.type === 'text'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-text>
                <badaso-password
                  v-if="config.type === 'password'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-password>
                <badaso-textarea
                  v-if="config.type === 'textarea'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-textarea>
                <badaso-checkbox
                  v-if="config.type === 'checkbox'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                  :items="config.options"
                ></badaso-checkbox>
                <badaso-search
                  v-if="config.type === 'search'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-search>
                <badaso-number
                  v-if="config.type === 'number'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-number>
                <badaso-url
                  v-if="config.type === 'url'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-url>
                <badaso-time
                  v-if="config.type === 'time'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-time>
                <badaso-date
                  v-if="config.type === 'date'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-date>
                <badaso-datetime
                  v-if="config.type === 'datetime'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                ></badaso-datetime>
                <badaso-select
                  v-if="config.type === 'select'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                  :items="config.options"
                ></badaso-select>
                <badaso-radio
                  v-if="config.type === 'radio'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                  :items="config.options"
                ></badaso-radio>
                <badaso-switch
                  v-if="config.type === 'switch'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-switch>
                <badaso-slider
                  v-if="config.type === 'slider'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-slider>
                <badaso-editor
                  v-if="config.type === 'editor'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="12"
                  v-model="config.value"
                ></badaso-editor>
                <badaso-tags
                  v-if="config.type === 'tags'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-tags>
                <badaso-hidden
                  v-if="config.type === 'hidden'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                ></badaso-hidden>

                <badaso-select-multiple
                  v-if="config.type === 'select_multiple'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  v-model="config.value"
                  size="10"
                  :items="config.options"
                ></badaso-select-multiple>
                <badaso-upload-image
                  v-if="config.type === 'upload_image'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-upload-image>
                <badaso-upload-file
                  v-if="config.type === 'upload_file'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-upload-file>
                <badaso-color-picker
                  v-if="config.type === 'color_picker'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-color-picker>

                <badaso-upload-image-multiple
                  v-if="config.type === 'upload_image_multiple'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-upload-image-multiple>
                <badaso-upload-file-multiple
                  v-if="config.type === 'upload_file_multiple'"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  v-model="config.value"
                ></badaso-upload-file-multiple>

                <vs-col vs-lg="2">
                  Action
                  <br />
                  <vs-button
                    color="primary"
                    type="relief"
                    @click.stop
                    @click="submitForm(config)"
                    v-if="$helper.isAllowed('edit_configurations')"
                    ><vs-icon icon="save"></vs-icon
                  ></vs-button>
                  <vs-button
                    color="danger"
                    type="relief"
                    @click.stop
                    @click="openConfirm(config.id)"
                    v-if="$helper.isAllowed('delete_configurations')"
                    ><vs-icon icon="delete"></vs-icon
                  ></vs-button>
                </vs-col>
              </vs-row>
            </vs-tab>
          </vs-tabs>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import _ from "lodash";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";
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
  },
  data: () => ({
    configurations: [],
    willDeleteConfigurationId: null,
  }),
  computed: {
    groupList: {
      get() {
        return this.$store.getters.getSiteGroup;
      },
    },
  },
  mounted() {
    this.getConfigurationList();
  },
  methods: {
    openConfirm(id) {
      this.willDeleteConfigurationId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteConfiguration,
        cancel: () => {
          this.willDeleteConfigurationId = null;
        },
      });
    },
    acceptAlert(color) {
      this.$vs.notify({
        color: "danger",
        title: "Deleted image",
        text: "The selected image was successfully deleted",
      });
    },
    getConfigurationList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.configuration
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.configurations = response.data.configurations;
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
    filterConfigurations(group) {
      console.log(this.configurations)
      console.log(group)
      return _.filter(this.configurations, ["group", group]);
    },
    deleteConfiguration() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.configuration
        .delete({
          id: this.willDeleteConfigurationId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getConfigurationList();
          this.$store.commit("FETCH_MENU");
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
    submitForm(config) {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.configuration
        .edit(this.$caseConvert.snake(config))
        .then((response) => {
          this.$vs.loading.close();
          this.getConfigurationList();
          this.$store.commit("FETCH_CONFIGURATION");
          this.$vs.notify({
            title: "Success",
            text: "Config Updated",
            color: "success",
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
  },
};
</script>

<style>
.vs-tabs--btn {
  font-size: 1.25rem;
  margin-bottom: 0px;
  font-family: inherit;
  font-weight: 700;
  line-height: 1.2;
}
.config-key {
  font-size: 1rem;
  margin-bottom: 15px;
  font-weight: bold;
  line-height: 1.2;
}
.inputx {
  margin-top: 5px;
}
</style>
