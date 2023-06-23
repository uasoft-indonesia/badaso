<template>
  <vs-col
    :vs-lg="size"
    vs-xs="12"
    class="badaso-upload-image-multiple__container"
  >
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
      class="badaso-upload-image-multiple__input--hidden"
      ref="image"
      :accept="availableMimetypes.image.validMime.join(',')"
      @change="onFilePicked"
      multiple
    />
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-upload-image-multiple__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span
          class="badaso-upload-image-multiple__input--error"
          v-html="alert"
        />
      </div>
    </div>
    <vs-row v-if="hasValue">
      <vs-col
        vs-lg="4"
        vs-sm="12"
        v-for="(val, index) in getImageModels"
        :key="index"
      >
        <div class="badaso-upload-image-multiple__preview">
          <vs-button
            class="badaso-upload-image-multiple__remove-button"
            color="danger"
            icon="close"
            @click="() => deleteSelectedImage(val)"
          />
          <img :src="val" class="badaso-upload-image-multiple__preview-image" />
        </div>
      </vs-col>
    </vs-row>

    <div
      class="badaso-upload-image-multiple__popup-dialog"
      tabindex="0"
      v-if="showFileManager"
    >
      <div class="badaso-upload-image-multiple__popup-container">
        <div class="badaso-upload-image-multiple__popup--top-bar">
          <h3>{{ $t("fileManager.title") }}</h3>
          <vs-spacer />
          <vs-button
            color="danger"
            type="relief"
            class="badaso-upload-image-multiple__popup-button--delete"
            v-if="getActiveTab !== 'url' && model"
            @click="openDeleteDialog"
          >
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>

        <ul class="badaso-upload-image-multiple__popup--left-bar">
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
          <li
            :class="{
              active: getActiveTab === 'url',
              'badaso-upload-image-multiple__menu--disabled':
                !$store.state.badaso.isOnline,
            }"
            @click="setActiveTab('url')"
          >
            Insert by URL
          </li>
        </ul>

        <div
          class="badaso-upload-image-multiple__popup--right-bar"
          v-if="getActiveTab !== 'url'"
        >
          <div
            class="badaso-upload-image-multiple__popup-add-image"
            @click="$refs.image.click()"
          >
            <vs-icon icon="add" color="#06bbd3" size="40px"></vs-icon>
          </div>
          <img
            :class="{
              active: model.includes(image.url),
              'badaso-upload-image-multiple__popup-image': true,
            }"
            :src="image.thumbUrl ? image.thumbUrl : image.url"
            v-for="(image, index) in images"
            :key="index"
            @click="selectImage(image.url)"
          />
        </div>

        <div
          v-if="getActiveTab === 'url'"
          class="badaso-upload-image-multiple__popup--right-bar badaso-upload-image-multiple__popup--url-bar"
        >
          <vs-input
            label="Paste an image URL here"
            placeholder="URL"
            v-model="model"
            @input="$openLoader()"
            description-text="If your URL is correct, you'll see an image preview here. Large images may take a few minutes to appear. Only accept PNG and JPEG."
          />
          <p
            v-if="isValidImageUrl === false && model.length > 0"
            class="is-error"
          >
            Only valid image (PNG and JPEG) is accepted
          </p>
          <img
            accept="image/png"
            :src="model"
            alt=""
            @load="
              isValidImageUrl = true;
              $closeLoader();
            "
            @error="
              isValidImageUrl = false;
              $closeLoader();
            "
            class="badaso-upload-image-multiple__preview--small"
          />
        </div>

        <div class="badaso-upload-image-multiple__popup--bottom-bar">
          <div class="badaso-upload-image-multiple__popup-button--footer">
            <div v-if="getActiveTab !== 'url'">
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
              class="badaso-upload-image-multiple__popup-button"
              @click="emitInput"
              :disabled="!model || model.length === 0"
            >
              {{ $t("button.submit") }}
            </vs-button>
            <vs-button
              color="danger"
              type="relief"
              class="badaso-upload-image-multiple__popup-button"
              @click="closeFileManager"
            >
              {{ $t("button.close") }}
            </vs-button>
          </div>
        </div>
      </div>
    </div>

    <vs-popup
      :title="$t('action.delete.title')"
      :active.sync="showDeleteImage"
      class="badaso-upload-image-multiple__popup-dialog--delete"
    >
      <p>{{ $t("action.delete.text") }}</p>
      <div class="badaso-upload-image-multiple__popup-dialog-content--delete">
        <vs-button color="primary" type="relief" @click="closeDeleteDialog">
          {{ $t("action.delete.cancel") }}
        </vs-button>
        <vs-button color="danger" type="relief" @click="deleteImage()">
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
  name: "BadasoUploadImageMultiple",
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
      default: "Upload Image Multiple",
    },
    value: {
      default: "",
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
      showDeleteImage: false,
      page: 1,
      images: [],
      paginator: {
        total: 1,
        perPage: 30,
      },
      model: [],
      isValidImageUrl: undefined,
    };
  },
  watch: {
    page: {
      handler() {
        this.getImages();
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
    getImageModels() {
      if (this.hasValue) {
        return JSON.parse(this.value);
      } else {
        return this.model;
      }
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
    deleteSelectedImage(url) {
      const index = this.getImageModels.indexOf(url);
      this.getImageModels.splice(index, 1);
      this.$emit("input", JSON.stringify(this.getImageModels));
    },
    selectImage(url) {
      if (this.model.includes(url)) {
        const idx = this.model.indexOf(url);
        this.model.splice(idx, 1);
      } else {
        this.model.push(url);
      }
    },
    resetState() {
      this.showFileManager = false;
      if (this.sharesOnly) {
        this.activeTab = "shares";
      } else {
        this.activeTab = "private";
      }
      this.showDeleteImage = false;
      this.page = 1;
      this.images = [];
      this.paginator = {
        total: 1,
        perPage: 30,
      };
      if (!this.hasValue) {
        this.model = [];
      }
      this.isValidImageUrl = undefined;
    },
    setActiveTab(tab) {
      if (this.getActiveTab !== tab) {
        this.activeTab = tab;
        this.model = [];
        this.page = 1;
        this.getImages();
      }
    },
    openFileManager() {
      this.showFileManager = true;
      this.disableScrollOnBody();
      this.getImages();
    },
    closeFileManager() {
      this.showFileManager = false;
      this.enableScrollOnBody();
      this.resetState();
    },
    openDeleteDialog() {
      this.showDeleteImage = true;
    },
    closeDeleteDialog() {
      this.showDeleteImage = false;
    },
    emitInput() {
      this.$emit("input", JSON.stringify(this.model));
      this.closeFileManager();
    },
    disableScrollOnBody() {
      disableBodyScroll(document.querySelector("body"));
    },
    enableScrollOnBody() {
      enableBodyScroll(document.querySelector("body"));
    },
    onFilePicked(e) {
      this.$refs.image.tabindex = -1;
      const files = e.target.files;
      for (const file of files) {
        if (file !== undefined) {
          if (file.size > this.availableMimetypes.image.maxSize * 100) {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: "Size too large (Max. 5MB)",
              color: "danger",
            });
            return;
          }
          if (!this.availableMimetypes.image.validMime.includes(file.type)) {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: "File type not allowed",
              color: "danger",
            });
            return;
          }
          this.uploadImage(file);
        }
      }
    },
    getImages() {
      if (this.getActiveFolder) {
        this.$openLoader();
        this.$api.badasoFile
          .browseUsingLfm({
            workingDir: this.getActiveFolder,
            type: "image",
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

            this.images = res.data.items;
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
    uploadImage(file) {
      const files = new FormData();
      files.append("upload", file);
      files.append("type", "image");
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
          this.getImages();
        });
    },
    deleteImage() {
      this.$openLoader();

      for (let index = 0; index < this.model.length; index++) {
        const element = this.model[index];
        console.log(element);
        const currentModel = _.filter(this.images, { url: element });
        this.$api.badasoFile
          .deleteUsingLfm({
            workingDir: this.getActiveFolder,
            type: "image",
            "items[]": currentModel[0].name,
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

            if (index + 1 === this.model.length) {
              this.getImages();
            }
          })
          .catch((error) => {
            console.log(error);
          })
          .finally(() => {
            if (index + 1 === this.model.length) {
              this.$closeLoader();
              this.closeDeleteDialog();
            }
          });
      }
    },
  },
};
</script>
