<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-upload-image__container">
    <vs-input :label="label" :placeholder="placeholder" @click="showOverlay" v-on:keyup.space="showOverlay" readonly v-model="imageUrl" icon="attach_file" icon-after="true" ></vs-input>
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12">
        <div class="badaso-upload-image__preview" v-if="imageUrl !== null && imageUrl !== ''">
          <vs-button class="badaso-upload-image__remove-button" color="danger" icon="close" @click="deleteFilePicked(value)" />
          <img :src="getImageSrc(value)" class="badaso-upload-image__preview-image" />
        </div>
      </vs-col>
    </vs-row>
    <div class="badaso-upload-image__popup-dialog" tabindex="0" v-if="show">
      <div class="badaso-upload-image__popup-container">
        <div class="badaso-upload-image__popup--top-bar">
          <h3>{{ $t("fileManager.title") }}</h3>
          <vs-spacer />
          <vs-button color="danger" type="relief" class="badaso-upload-image__popup-button--delete" v-if="getSelected !== 'url' && isImageSelected" @click="openDialog">
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>
        <ul class="badaso-upload-image__popup--left-bar">
          <li :class="[getSelected === 'private' ? 'active' : '']" @click="selected = 'private'" v-if="privateOnly || (!privateOnly && !sharesOnly)" >Private</li>
          <li :class="[getSelected === 'shares' ? 'active' : '']" @click="selected = 'shares'" v-if="sharesOnly || (!sharesOnly && !privateOnly)">Shares</li>
          <li :class="[getSelected === 'url' ? 'active' : '']" @click="selected = 'url'">Insert by URL</li>
        </ul>
        <div class="badaso-upload-image__popup--right-bar" v-if="getSelected !== 'url'">
          <div class="badaso-upload-image__popup-add-image" @click="pickFile">
            <vs-icon icon="add" color="#06bbd3" size="75px"></vs-icon>
          </div>
          <img :class="[activeImage === index ? 'active' : '', 'badaso-upload-image__popup-image']" :src="item.thumbUrl" v-for="(item, index) in images.items" :key="index" @click="activeImage = index; isImageSelected = true; " />
        </div>
        <div v-if="getSelected === 'url'" class="badaso-upload-image__popup--right-bar badaso-upload-image__popup--url-bar">
          <vs-input v-if="getSelected === 'url'" label="Paste an image URL here" placeholder="URL" v-model="inputByUrl" description-text="If your URL is correct, you'll see an image preview here. Large images may take a few minutes to appear. Only accept PNG and JPEG." @input="inputByUrl === '' ? (dirty = false) : (dirty = true)"></vs-input>
          <p v-if="!isValidImage && getSelected === 'url' && dirty" class="badaso-upload-image__input--error">
            Only valid image (PNG and JPEG) is accepted
          </p>
          <img accept="image/png" v-if="getSelected === 'url'" :src="inputByUrl" alt="" @load="isValidImage = true" @error="isValidImage = false" class="badaso-upload-image__preview--small" />
        </div>
        <div class="badaso-upload-image__popup--bottom-bar">
          <div class="badaso-upload-image__popup-button--footer">
            <vs-button color="primary" type="relief" @click="emitInput" :disabled="isSubmitDisable">
              {{ $t("button.submit") }}
            </vs-button>
            <vs-button color="danger" type="relief" @click="closeOverlay">
              {{ $t("button.close") }}
            </vs-button>
          </div>
        </div>
      </div>
    </div>
    <input type="file" class="badaso-upload-image__input--hidden" ref="image" accept="image/*" @change="onFilePicked"/>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p
          class="badaso-upload-image__input--error"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        ></p>
      </div>
      <div v-else>
        <span class="badaso-upload-image__input--error" v-html="alert"></span>
      </div>
    </div>
    <vs-popup :title="$t('action.delete.title')" :active.sync="dialog" class="badaso-upload-image__popup-dialog--delete">
      <p>{{ $t("action.delete.text") }}</p>
      <div class="badaso-upload-image__popup-dialog-content--delete">
        <vs-button color="primary" type="relief" @click="dialog = false">{{
          $t("action.delete.cancel")
        }}</vs-button>
        <vs-button color="danger" type="relief" @click="deleteImage">{{
          $t("action.delete.accept")
        }}</vs-button>
      </div>
    </vs-popup>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoUploadImage",
  props: {
    size: {
      type: String,
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
      type: String,
      default: "",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String | Array,
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
      dialog: false,
      activeImage: null,
      show: false,
      selected: "private",
      images: {
        display: "",
        items: [],
        paginator: {},
      },
      files: [],
      imageUrl: "",
      inputByUrl: "",
      isValidImage: false,
      isImageSelected: false,
      dirty: false,
    };
  },
  computed: {
    getSelected() {
      this.activeImage = null;
      return this.selected;
    },
    getSelectedFolder() {
      if (this.getSelected === "shares") return "/shares";
      else if (this.getSelected === "url") return;
      else return this.getUserFolder;
    },
    getUserFolder() {
      return "/" + this.$store.state.badaso.user.id;
    },
    isSubmitDisable() {
      if (!this.isImageSelected && this.selected !== "url") {
        return true;
      }

      if (!this.isValidImage && this.selected === "url") {
        return true;
      }

      if (this.activeImage === null && this.selected !== "url") {
        return true;
      }

      return false;
    },
  },
  mounted() {
    if (this.sharesOnly) {
      this.selected = "shares";
    }

    this.imageUrl = this.value;
  },
  watch: {
    selected: {
      handler(val) {
        this.getImages();
        this.isImageSelected = false;
      },
    },
    value: {
      handler(val) {
        this.imageUrl = val;
      },
    },
  },
  methods: {
    pickFile() {
      this.$refs.image.click();
    },
    showOverlay() {
      this.show = true;
      document.body.style.setProperty("position", "fixed");
      this.getImages();
    },
    closeOverlay() {
      this.show = false;
      this.activeImage = null;
      document.body.style.setProperty("position", "relative");
    },
    onFilePicked(e) {
      this.$refs.image.tabindex = -1;
      const files = e.target.files;
      if (files[0] !== undefined) {
        if (files[0].size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }

        this.files = files;
        this.uploadImage();
      }
    },
    uploadImage() {
      const files = new FormData();
      files.append("upload", this.files[0]);
      files.append("working_dir", this.getSelectedFolder);
      this.$api.badasoFile
        .uploadUsingLfm(files)
        .then((res) => {
          this.getImages();
        })
        .catch((error) => {
          console.error(error);
        });
    },
    isString(str) {
      if (typeof str === "string" || str instanceof String) return true;
      else return false;
    },
    getImageSrc(value) {
      if (this.$helper.isValidHttpUrl(value)) {
        return value
      }

      return this.$store.state.badaso.meta.mediaBaseUrl + value
    },
    getImages() {
      this.images.items = [];
      if (this.getSelectedFolder) {
        this.$api.badasoFile
          .browseUsingLfm({
            workingDir: this.getSelectedFolder,
          })
          .then((res) => {
            this.images = res.data;
            this.images.items = res.data.items.filter((val) => {
              return val.thumbUrl !== null;
            });
          })
          .catch((error) => {
            console.log(error);
          });
      }
    },
    emitInput() {
      if (this.selected !== "url") {
        var url = this.images.items[this.activeImage].url.replace(this.$store.state.badaso.meta.mediaBaseUrl, "");
        this.$emit("input", url);
      } else {
        this.$emit("input", this.inputByUrl);
      }
      this.closeOverlay();
    },
    deleteImage() {
      this.$api.badasoFile
        .deleteUsingLfm({
          workingDir: this.getSelectedFolder,
          "items[]": this.images.items[this.activeImage].name,
        })
        .then((res) => {
          this.getImages();
        })
        .catch((error) => {
          console.log(error);
        });

      this.activeImage = null;
      this.dialog = false;
    },
    openDialog() {
      this.dialog = true;
    },
    deleteFilePicked(item) {
      if (item === null || item === undefined) return;

      if (typeof item === "string" && item !== "") this.$emit("input", null);
      if (typeof item === "object") {
        this.$emit("input", null);
      }
    },
  },
};
</script>
