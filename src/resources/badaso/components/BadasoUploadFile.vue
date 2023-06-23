<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-upload-file__container">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="openFileManager"
      v-on:keyup.space="openFileManager"
      readonly
      v-model="value"
      icon="attach_file"
      icon-after="true"
    />
    <input
      type="file"
      class="badaso-upload-file__input--hidden"
      ref="file"
      :accept="availableMimetypes.file.validMime.join(',')"
      @change="onFilePicked"
    />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-upload-file__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-upload-file__input--error" v-html="alert"></span>
      </div>
    </div>
    <vs-row v-if="hasValue">
      <vs-col vs-lg="4" vs-sm="12">
        <div class="badaso-upload-file__preview">
          <vs-button
            class="badaso-upload-file__remove-button"
            color="danger"
            icon="close"
            @click="$emit('input', null)"
          />
          <div class="badaso-upload-file__preview-text">
            <a target="_blank" :href="$api.badasoFile.download(value)">
              {{ value.split("/").reverse()[0] | truncate }}
            </a>
          </div>
        </div>
      </vs-col>
    </vs-row>

    <div
      class="badaso-upload-file__popup-dialog"
      tabindex="0"
      v-if="showFileManager"
    >
      <div class="badaso-upload-file__popup-container">
        <div class="badaso-upload-file__popup--top-bar">
          <h3>{{ $t("fileManager.title") }}</h3>
          <vs-spacer />
          <vs-button
            color="danger"
            type="relief"
            class="badaso-upload-file__popup-button--delete"
            v-if="model"
            @click="openDeleteDialog"
          >
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>

        <ul class="badaso-upload-file__popup--left-bar">
          <li
            :class="{ active: getActiveTab === 'private' }"
            @click="setActiveTab('private')"
            v-if="privateOnly || (!privateOnly && !sharesOnly)"
          >
            Private
          </li>
          <li
            :class="{ active: getActiveTab === 'shares' }"
            @click="setActiveTab('shares')"
            v-if="sharesOnly || (!sharesOnly && !privateOnly)"
          >
            Shares
          </li>
        </ul>

        <div class="badaso-upload-file__popup--right-bar">
          <div
            class="badaso-upload-file__popup-add-file"
            @click="$refs.file.click()"
          >
            <vs-icon icon="add" color="#06bbd3" size="40px"></vs-icon>
          </div>
          <div
            v-for="(file, index) in files"
            :key="index"
            @click="model = file.url"
            class="badaso-upload-file__popup-file-container"
          >
            <div
              :class="{
                active: model === file.url,
                'badaso-upload-file__popup-file': true,
              }"
            >
              <vs-icon
                icon="insert_drive_file"
                size="45px"
                color="#06bbd3"
              ></vs-icon>
              <p class="badaso-upload-file__popup-file-text">
                {{ file.name | truncate }}
              </p>
            </div>
          </div>
        </div>

        <div class="badaso-upload-file__popup--bottom-bar">
          <div class="badaso-upload-file__popup-button--footer">
            <div>
              <vs-pagination
                :total="Math.ceil(paginator.total / paginator.perPage)"
                v-model="page"
                :max="1"
              ></vs-pagination>
            </div>
            <vs-spacer />
            <vs-button
              color="primary"
              type="relief"
              @click="emitInput"
              :disabled="!model"
              class="badaso-upload-file__popup-button"
            >
              {{ $t("button.submit") }}
            </vs-button>
            <vs-button
              color="danger"
              type="relief"
              @click="closeFileManager"
              class="badaso-upload-file__popup-button"
            >
              {{ $t("button.close") }}
            </vs-button>
          </div>
        </div>
      </div>
    </div>

    <vs-popup
      :title="$t('action.delete.title')"
      :active.sync="showDeleteFile"
      class="badaso-upload-file__popup-dialog--delete"
    >
      <p>{{ $t("action.delete.text") }}</p>
      <div class="badaso-upload-file__popup-dialog-content--delete">
        <vs-button color="primary" type="relief" @click="closeDeleteDialog">
          {{ $t("action.delete.cancel") }}
        </vs-button>
        <vs-button color="danger" type="relief" @click="deleteFile()">
          {{ $t("action.delete.accept") }}
        </vs-button>
      </div>
    </vs-popup>
  </vs-col>
</template>

<script>
import { mapState } from "vuex";
import * as _ from "lodash";
import { disableBodyScroll, enableBodyScroll } from "body-scroll-lock";
export default {
  name: "BadasoUploadFile",
  props: {
    size: {
      type: String || Number,
      default: "12",
    },
    label: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Upload File",
    },
    value: {
      default: null,
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String || Array,
      default: "",
    },
    sharesOnly: {
      type: Boolean,
    },
    privateOnly: {
      type: Boolean,
    },
  },
  data() {
    return {
      showFileManager: false,
      activeTab: "private",
      showDeleteFile: false,
      page: 1,
      files: [],
      paginator: {
        total: 1,
        perPage: 30,
      },
      model: null,
    };
  },
  filters: {
    truncate(val) {
      return _.truncate(val, {
        length: 15,
      });
    },
  },
  watch: {
    page: {
      handler() {
        this.getFiles();
      },
      immediate: false,
    },
  },
  created() {
    if (this.sharesOnly) {
      this.activeTab = "shares";
    }
  },
  computed: {
    getActiveTab() {
      return this.activeTab;
    },
    getActiveFolder() {
      switch (this.getActiveTab) {
        case "url":
          return;
        case "shares":
          return "/shares";
        default:
          return `/${this.userId}`;
      }
    },
    hasValue() {
      return (
        this.value !== null &&
        this.value !== "null" &&
        this.value !== "" &&
        this.value !== "[]" &&
        this.value !== "{}"
      );
    },
    ...mapState({
      userId(state) {
        return state.badaso.user.id;
      },
      availableMimetypes(state) {
        return state.badaso.availableMimetypes;
      },
    }),
  },
  methods: {
    resetState() {
      this.showFileManager = false;
      if (this.sharesOnly) {
        this.activeTab = "shares";
      } else {
        this.activeTab = "private";
      }
      this.showDeleteFile = false;
      this.page = 1;
      this.files = [];
      this.paginator = {
        total: 1,
        perPage: 30,
      };
      if (!this.hasValue) {
        this.model = null;
      }
    },
    setActiveTab(tab) {
      if (this.getActiveTab !== tab) {
        this.activeTab = tab;
        this.model = null;
        this.page = 1;
        this.getFiles();
      }
    },
    openFileManager() {
      this.showFileManager = true;
      this.disableScrollOnBody();
      this.getFiles();
    },
    closeFileManager() {
      this.showFileManager = false;
      this.enableScrollOnBody();
      this.resetState();
    },
    openDeleteDialog() {
      this.showDeleteFile = true;
    },
    closeDeleteDialog() {
      this.showDeleteFile = false;
    },
    emitInput() {
      this.$emit("input", this.model);
      this.closeFileManager();
    },
    disableScrollOnBody() {
      disableBodyScroll(document.querySelector("body"));
    },
    enableScrollOnBody() {
      enableBodyScroll(document.querySelector("body"));
    },
    onFilePicked(e) {
      this.$refs.file.tabindex = -1;
      const files = e.target.files;
      if (files[0] !== undefined) {
        if (files[0].size > this.availableMimetypes.file.maxSize * 100) {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: "Size too large (Max. 5MB)",
            color: "danger",
          });
          return;
        }
        if (!this.availableMimetypes.file.validMime.includes(files[0].type)) {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: "File type not allowed",
            color: "danger",
          });
          return;
        }
        this.uploadFile(files[0]);
      }
    },
    getFiles() {
      if (this.getActiveFolder) {
        this.$openLoader();
        this.$api.badasoFile
          .browseUsingLfm({
            workingDir: this.getActiveFolder,
            type: "file",
            page: this.page,
          })
          .then((res) => {
            const error = _.get(res, "data.original.error", null);
            if (error) {
              this.$vs.notify({
                title: this.$t("alert.danger"),
                text: error.message,
                color: "danger",
              });
            }

            this.files = res.data.items;
            this.paginator = res.data.paginator;
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => {
            this.$closeLoader();
          });
      }
    },
    uploadFile(file) {
      const files = new FormData();
      files.append("upload", file);
      files.append("working_dir", this.getActiveFolder);
      this.$api.badasoFile
        .uploadUsingLfm(files)
        .then((res) => {
          const error = _.get(res, "data.original.error", null);
          if (error) {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          } else {
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: "Upload successful",
              color: "success",
            });
          }
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        })
        .finally(() => {
          this.getFiles();
        });
    },
    deleteFile() {
      this.$openLoader();
      this.$api.badasoFile
        .deleteUsingLfm({
          workingDir: this.getActiveFolder,
          "items[]": _.find(this.files, { url: this.model }).name,
        })
        .then((res) => {
          const error = _.get(res, "data.original.error", null);
          if (error) {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }

          this.getFiles();
        })
        .catch((error) => {
          console.log(error);
        })
        .finally(() => {
          this.$closeLoader();
          this.closeDeleteDialog();
        });
    },
  },
};
</script>
