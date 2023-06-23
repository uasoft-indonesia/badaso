<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          v-if="$helper.isAllowed('add_configurations')"
          color="primary"
          type="relief"
          :to="{ name: 'SiteManagementAdd' }"
        >
          <vs-icon icon="add" /> {{ $t("action.add") }}
        </vs-button>
      </template>
    </badaso-breadcrumb-row>
    <vs-row
      v-if="$helper.isAllowed('browse_configurations') && groupList.length > 0"
    >
      <vs-col vs-lg="12">
        <vs-card>
          <vs-tabs :color="adminPanelHeaderFontColor">
            <vs-tab
              v-for="(group, index) in groupList"
              :key="index"
              :label="group.label"
            >
              <vs-row
                v-for="(config, index) in filterConfigurations(group.value)"
                :key="index"
                class="site-management__container"
              >
                <badaso-text
                  v-if="config.type === 'text'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-email
                  v-if="config.type === 'email'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-password
                  v-if="config.type === 'password'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-textarea
                  v-if="config.type === 'textarea'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-checkbox
                  v-if="config.type === 'checkbox'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :items="config.details.items"
                />
                <badaso-search
                  v-if="config.type === 'search'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-number
                  v-if="config.type === 'number'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-url
                  v-if="config.type === 'url'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-time
                  v-if="config.type === 'time'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-date
                  v-if="config.type === 'date'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-datetime
                  v-if="config.type === 'datetime'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-select
                  v-if="config.type === 'select'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :items="config.details.items"
                />
                <badaso-radio
                  v-if="config.type === 'radio'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :items="config.details.items"
                />
                <badaso-switch
                  v-if="config.type === 'switch'"
                  v-model="config.value"
                  :label="config.displayName"
                  size="10"
                  :status="statusMaintenance"
                />
                <badaso-slider
                  v-if="config.type === 'slider'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-editor
                  v-if="config.type === 'editor'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-tags
                  v-if="config.type === 'tags'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />
                <badaso-hidden
                  v-if="config.type === 'hidden'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                />

                <badaso-select-multiple
                  v-if="config.type === 'select_multiple'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :items="config.details.items"
                />
                <badaso-upload-image
                  v-if="config.type === 'upload_image'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :private-only="
                    config.details !== null &&
                    config.details.type === 'private-only'
                  "
                  :shares-only="
                    config.details !== null &&
                    config.details.type === 'shares-only'
                  "
                />
                <badaso-upload-file
                  v-if="config.type === 'upload_file'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :private-only="
                    config.details !== null &&
                    config.details.type === 'private-only'
                  "
                  :shares-only="
                    config.details !== null &&
                    config.details.type === 'shares-only'
                  "
                />
                <badaso-color-picker
                  v-if="config.type === 'color_picker'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                />

                <badaso-upload-image-multiple
                  v-if="config.type === 'upload_image_multiple'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  size="10"
                  :private-only="
                    config.details !== null &&
                    config.details.type === 'private-only'
                  "
                  :shares-only="
                    config.details !== null &&
                    config.details.type === 'shares-only'
                  "
                />
                <badaso-upload-file-multiple
                  v-if="config.type === 'upload_file_multiple'"
                  v-model="config.value"
                  :label="config.displayName"
                  :placeholder="config.value"
                  :private-only="
                    config.details !== null &&
                    config.details.type === 'private-only'
                  "
                  :shares-only="
                    config.details !== null &&
                    config.details.type === 'shares-only'
                  "
                  size="10"
                />

                <vs-col vs-lg="2">
                  <br />
                  <vs-button
                    v-if="
                      $helper.isAllowed('delete_configurations') &&
                      config.canDelete
                    "
                    color="danger"
                    type="relief"
                    @click.stop
                    @click="openConfirm(config.id)"
                  >
                    <vs-icon icon="delete" />
                  </vs-button>
                </vs-col>
              </vs-row>
            </vs-tab>
          </vs-tabs>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button
                color="primary"
                type="relief"
                @click="submitMultipleEdit"
              >
                <vs-icon icon="save" /> {{ $t("site.edit.multiple") }}
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import _ from "lodash";

export default {
  name: "SiteManagementBrowse",
  components: {},
  data: () => ({
    configurations: [],
    role: [],
    willDeleteConfigurationId: null,
    statusMaintenance: import.meta.env.VITE_BADASO_MAINTENANCE,
  }),
  computed: {
    groupList: {
      get() {
        return this.$store.getters["badaso/getSiteGroup"];
      },
    },
    adminPanelHeaderFontColor: {
      get() {
        return "#06bbd3";
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
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteConfiguration,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteConfigurationId = null;
        },
      });
    },
    acceptAlert(color) {
      this.$vs.notify({
        color: "danger",
        title: this.$t("site.deletedImage.title"),
        text: this.$t("site.deletedImage.text"),
      });
    },
    getConfigurationList() {
      this.$openLoader();
      this.$api.badasoRole
        .browse()
        .then((response) => {
          response.data.roles.map((data) => {
            const temp = { label: data.displayName, value: data.name };
            this.role.push(temp);
            return data;
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
      this.$api.badasoConfiguration
        .browse()
        .then((response) => {
          this.$closeLoader();
          const configurations = response.data.configurations.map((data) => {
            try {
              data.details = JSON.parse(data.details);
              if (data.type === "hidden") {
                data.value = data.details.value ? data.details.value : "";
              }
              if (data.key === "defaultRoleRegistration") {
                data.details.items = this.role;
              }
              if (data.type === "switch") {
                data.value = data.value == "1";
              }
              const typeRequiredItems = [
                "checkbox",
                "radio",
                "select",
                "select_multiple",
              ];
              if (typeRequiredItems.includes(data.type)) {
                if (!data.details || !data.details.items) {
                  data.details = {};
                  data.details.items = [];
                  this.$vs.notify({
                    title: this.$t("alert.danger"),
                    text: "Invalid options for Checkbox, Radio, Select, Select-multiple.",
                    color: "danger",
                  });
                }
              }
            } catch (error) {}
            return data;
          });
          this.configurations = JSON.parse(JSON.stringify(configurations));
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
    filterConfigurations(group) {
      return _.filter(this.configurations, ["group", group]);
    },
    deleteConfiguration() {
      this.$openLoader();
      this.$api.badasoConfiguration
        .delete({
          id: this.willDeleteConfigurationId,
        })
        .then((response) => {
          this.$closeLoader();
          this.getConfigurationList();
          this.$store.commit("badaso/FETCH_MENU");
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
    submitForm(config) {
      this.$openLoader();
      this.$api.badasoConfiguration
        .edit(this.$caseConvert.snake(config))
        .then((response) => {
          this.$closeLoader();
          this.getConfigurationList();
          this.$store.commit("badaso/FETCH_CONFIGURATION");
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: this.$t("site.configUpdated"),
            color: "success",
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
    submitMultipleEdit() {
      this.$openLoader();
      this.$api.badasoConfiguration
        .editMultiple({ configurations: this.configurations })
        .then((response) => {
          this.$closeLoader();
          this.getConfigurationList();
          this.$store.commit("badaso/FETCH_CONFIGURATION");
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: this.$t("site.configUpdated"),
            color: "success",
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
  },
};
</script>
