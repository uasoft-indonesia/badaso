<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="showOverlay"
      v-on:keyup.space="showOverlay"
      readonly
      v-model="imageUrl"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12">
        <div class="image-container" v-if="imageUrl !== null && imageUrl !== ''">
          <vs-button
            class="delete-image"
            color="danger"
            icon="close"
            @click="deleteFilePicked(value)"
          ></vs-button>
          <img :src="$store.state.badaso.meta.mediaBaseUrl + value" class="image" />
        </div>
      </vs-col>
    </vs-row>
    <div class="badaso-popup-dialog" tabindex="0" v-if="show">
      <div class="badaso-popup-container">
        <div class="top">
          <h3>{{ $t('fileManager.title') }}</h3>
          <vs-spacer />
          <vs-button color="danger" type="relief" class="mr-2" v-if="getSelected !== 'url' &&   isImageSelected" @click="openDialog">
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>
        <ul class="left">
          <li :class="[getSelected === 'private'  ? 'active' : '' ]" @click="selected = 'private'" v-if="privateOnly || !privateOnly && !sharesOnly">Private</li>
          <li :class="[getSelected === 'shares' ? 'active' : '' ]" @click="selected = 'shares'" v-if="sharesOnly || !sharesOnly && !privateOnly">Shares</li>
          <li :class="[getSelected === 'url' ? 'active' : '' ]" @click="selected = 'url'">Insert by URL</li>
        </ul>
        <div class="right" v-if="getSelected !== 'url'">
          <div class="add-image" @click="pickFile">
            <vs-icon icon="add" color="#06bbd3" size="75px"></vs-icon>
          </div>
          <img :class="[activeImage === index ? 'active' : '']" :src="item.thumbUrl" v-for="(item, index) in images.items" :key="index" @click="activeImage = index; isImageSelected = true">
        </div>
        <div v-if="getSelected === 'url'" class="right url">
          <vs-input
            v-if="getSelected === 'url'"
            label="Paste an image URL here"
            placeholder="URL"
            v-model="inputByUrl"
            description-text="If your URL is correct, you'll see an image preview here. Large images may take a few minutes to appear. Only accept PNG and JPEG."
            @input="inputByUrl === '' ? dirty = false : dirty = true"
          ></vs-input>
          <p v-if="!imageValidation && getSelected === 'url' && dirty" style="color: var(--danger)">Only valid image (PNG and JPEG) is accepted</p>
          <img accept="image/png" v-if="getSelected === 'url'" :src="inputByUrl" alt="" @load="isValidImage = true" @error="isValidImage = false" class="small">
        </div>
        <div class="bottom">
          <div class="close-button">
            <vs-button color="primary" type="relief" @click="emitInput" :disabled="isSubmitDisable">
              {{ $t('button.submit') }}
            </vs-button>
            <vs-button color="danger" type="relief" @click="closeOverlay">
              {{ $t('button.close') }}
            </vs-button>
          </div>
        </div>
      </div>
    </div>
    <input
      type="file"
      style="display: none"
      ref="image"
      accept="image/*"
      @change="onFilePicked"
    />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p
          class="text-danger"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        ></p>
      </div>
      <div v-else>
        <span class="text-danger" v-html="alert"></span>
      </div>
    </div>
    <vs-popup :title="$t('action.delete.title')" :active.sync="dialog" style="z-index: 26000;">
      <p>{{ $t('action.delete.text') }}</p>
      <div style="float: right">
        <vs-button color="primary" type="relief" @click="dialog = false">{{ $t('action.delete.cancel') }}</vs-button>
        <vs-button color="danger" type="relief" @click="deleteImage">{{ $t('action.delete.accept') }}</vs-button>
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
      default: ""
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
      selected: 'private',
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
    imageValidation() {
      if (this.$helper.isValidHttpUrl(this.inputByUrl)) {
        let url = this.inputByUrl.split('.')
        let extension = url[url.length - 1]
        if (extension === 'png' || extension === 'jpg' || extension === 'jpeg') {
          return true
        }
      }
      return false
    },
    getSelected() {
      this.activeImage = null
      return this.selected
    },
    getSelectedFolder() {
      if (this.getSelected === 'shares') return '/shares'
      else if (this.getSelected === 'url') return
      else return this.getUserFolder
    },
    getUserFolder() {
      return '/' + this.$store.state.badaso.user.id
    },
    isSubmitDisable() {
      if (!this.isImageSelected && this.selected !== 'url') {
        return true
      }

      if (!this.imageValidation && this.selected === 'url') {
        return true
      }

      if (this.activeImage === null && this.selected !== 'url') {
        return true
      }

      return false
    }
  },
  mounted() {
    if (this.sharesOnly) {
      this.selected = "shares"
    }

    this.imageUrl = this.value
  },
  watch: {
    selected: {
      handler(val) {
        this.getImages()
        this.isImageSelected = false
      }
    },
    value: {
      handler(val) {
        this.imageUrl = val
      }
    }
  },
  methods: {
    pickFile() {
      this.$refs.image.click();
    },
    showOverlay() {
      this.show = true
      document.body.style.setProperty('position', 'fixed')
      this.getImages()
    },
    closeOverlay() {
      this.show = false
      this.activeImage = null
      document.body.style.setProperty('position', 'relative')
    },
    onFilePicked(e) {
      this.$refs.image.tabindex = -1;
      const files = e.target.files;
      if (files[0] !== undefined) {
        if (files[0].size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }

        this.files = files
        this.uploadImage()
      }
    },
    uploadImage() {
      const files = new FormData()
      files.append('upload', this.files[0])
      files.append('working_dir', this.getSelectedFolder)
      this.$api.badasoFile.uploadUsingLfm(files)
      .then(res => {
        this.getImages()
      }).catch(error => {
        console.error(error);
      })
    },
    isString(str) {
      if (typeof str === "string" || str instanceof String) return true;
      else return false;
    },
    getImages() {
      this.images.items = []
      if (this.getSelectedFolder) {
        this.$api.badasoFile.browseUsingLfm({
          workingDir: this.getSelectedFolder
        })
        .then(res => {
          this.images = res.data
          this.images.items = res.data.items.filter(val => {
            return val.thumbUrl !== null
          })
        })
        .catch(error => {
          console.log(error);
        })
      }
    },
    emitInput() {
      if (this.selected !== 'url') {
        var url = this.images.items[this.activeImage].url.replace(this.$store.state.badaso.meta.mediaBaseUrl, '')
        this.$emit('input', url)
      } else {
        this.$emit('input', this.inputByUrl)
      }
      this.closeOverlay()
    },
    deleteImage() {
      this.$api.badasoFile.deleteUsingLfm({
        workingDir: this.getSelectedFolder,
        'items[]': this.images.items[this.activeImage].name
      })
      .then(res => {
        this.getImages()
      })
      .catch(error => {
        console.log(error);
      })

      this.activeImage = null
      this.dialog = false
    },
    openDialog() {
      this.dialog = true
    },
    deleteFilePicked(item) {
      if (item === null || item === undefined) return

      if (typeof item === 'string' && item !== '') this.$emit('input', null)
      if (typeof item === 'object') {
        this.$emit('input', null)
      }
    }
  },
};
</script>

<style>
.image-container {
  width: 100% !important;
  border: solid 1px #dedede;
  box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.1);
  margin: unset;
  margin-top: 10px;
  max-width: unset;
  position: relative;
}
.image {
  width: 100%;
}

.delete-image {
  opacity: 0;
  position: absolute;
  top: 4px;
  right: 4px;
  transition: all 0.2s ease;
}

.image-container:hover .delete-image {
  opacity: 1;
}
</style>
