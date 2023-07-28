<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-upload-image__container">
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
      class="badaso-upload-image__input--hidden"
      ref="image"
      :accept="availableMimetypes.image.validMime.join(',')"
      @change="onFilePicked"
    />
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p
          class="badaso-upload-image__input--error"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        />
      </div>
      <div v-else>
        <span class="badaso-upload-image__input--error" v-html="alert" />
      </div>
    </div>
    <vs-row v-if="hasValue">
      <vs-col vs-lg="4" vs-sm="12">
        <div class="badaso-upload-image__preview">
          <vs-button
            class="badaso-upload-image__remove-button"
            color="danger"
            icon="close"
            @click="$emit('input', null)"
          />
          <img :src="value" class="badaso-upload-image__preview-image" />
        </div>
      </vs-col>
    </vs-row>

    <div
      class="badaso-upload-image__popup-dialog"
      tabindex="0"
      v-if="showFileManager"
    >
      <div class="badaso-upload-image__popup-container">
        <div class="badaso-upload-image__popup--top-bar">
          <h3>{{ $t("fileManager.title") }}</h3>
          <vs-spacer />
          <badaso-select
            v-model="sortTypeValue"
            size="2"
            style="margin-bottom: 0px !important; margin-right: 1rem"
            placeholder="Sort Type"
            :items="sortTypeList"
            @input="sortImages"
          >
          </badaso-select>
          <vs-button
            color="danger"
            type="relief"
            class="badaso-upload-image__popup-button--delete"
            v-if="getActiveTab !== 'url' && model"
            @click="openDeleteDialog"
          >
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>

        <ul class="badaso-upload-image__popup--left-bar">
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
              'badaso-upload-image__menu--disabled':
                !$store.state.badaso.isOnline,
            }"
            @click="setActiveTab('url')"
          >
            Insert by URL
          </li>
        </ul>

        <div
          class="badaso-upload-image__popup--right-bar"
          v-if="getActiveTab !== 'url'"
        >
          <div
            class="badaso-upload-image__popup-add-image"
            @click="$refs.image.click()"
          >
            <vs-icon icon="add" color="#06bbd3" size="40px"></vs-icon>
          </div>
          <img
            :class="{
              active: model === image.url,
              'badaso-upload-image__popup-image': true,
            }"
            :src="image.thumbUrl ? image.thumbUrl : image.url"
            v-for="(image, index) in images"
            :key="index"
            @click="model = image.url"
          />
        </div>

        <div
          v-if="getActiveTab === 'url'"
          class="badaso-upload-image__popup--right-bar badaso-upload-image__popup--url-bar"
        >
          <vs-input
            label="Paste an image URL here"
            placeholder="URL"
            v-model="model"
            @input="$openLoader()"
            description-text="If your URL is correct, you'll see an image preview here. Large images may take a few minutes to appear. Only accept PNG and JPEG."
          />
          <p
            v-if="isValidImageUrl === false && model"
            class="badaso-upload-image__input--error"
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
            class="badaso-upload-image__preview--small"
          />
        </div>

        <div class="badaso-upload-image__popup--bottom-bar">
          <div class="badaso-upload-image__popup-button--footer">
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
              @click="emitInput"
              :disabled="!model"
              class="badaso-upload-image__popup-button"
            >
              {{ $t("button.submit") }}
            </vs-button>
            <vs-button
              color="danger"
              class="badaso-upload-image__popup-button"
              type="relief"
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
      class="badaso-upload-image__popup-dialog--delete"
    >
      <p>{{ $t("action.delete.text") }}</p>
      <div class="badaso-upload-image__popup-dialog-content--delete">
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
  name: "BadasoUploadImage",
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
      default: "Upload Image",
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
      showDeleteImage: false,
      page: 1,
      images: [],
      paginator: {
        total: 1,
        perPage: 30,
      },
      isValidImageUrl: undefined,
      model: null,
      sortTypeValue: "",
      sortTypeList: [
        {
          label: "Time",
          value: "time",
        },
        {
          label: "Alphabet",
          value: "alphabet",
        },
      ],
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
      this.showDeleteImage = false;
      this.page = 1;
      this.images = [];
      this.paginator = {
        total: 1,
        perPage: 30,
      };
      if (!this.hasValue) {
        this.model = null;
      }
      this.isValidImageUrl = undefined;
    },
    setActiveTab(tab) {
      if (this.getActiveTab !== tab) {
        this.activeTab = tab;
        this.model = null;
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
      this.$refs.image.tabindex = -1;
      const files = e.target.files;
      if (files[0] !== undefined) {
        if (files[0].size > this.availableMimetypes.image.maxSize * 100) {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: "Size too large (Max. 5MB)",
            color: "danger",
          });
          return;
        }
        if (!this.availableMimetypes.image.validMime.includes(files[0].type)) {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: "File type not allowed",
            color: "danger",
          });
          return;
        }
        this.uploadImage(files[0]);
      }
    },
    sortImages(event) {
      this.getImages(event);
    },
    getImages(sortType) {
      if (this.getActiveFolder) {
        this.$openLoader();
        this.$api.badasoFile
          .browseUsingLfm({
            workingDir: this.getActiveFolder,
            type: "image",
            sort_type: sortType || "time",
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
      this.$api.badasoFile
        .deleteUsingLfm({
          workingDir: this.getActiveFolder,
          type: "image",
          "items[]": _.find(this.images, { url: this.model }).name,
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

          this.getImages();
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
