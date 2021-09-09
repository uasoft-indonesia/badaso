<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-upload-file__container">
    <vs-input :label="label" :placeholder="placeholder" @click="showOverlay" v-on:keyup.space="showOverlay" readonly v-model="fileData" icon="attach_file" icon-after="true" />
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12">
        <div class="badaso-upload-file__preview" v-if="isString(value) && value !== ''">
          <vs-button class="badaso-upload-file__remove-button" color="danger" icon="close" @click="deleteFilePicked(value)" />
          <div class="badaso-upload-file__preview-text"> 
            <a target="_blank" :href="`${$api.badasoFile.download(value)}`">{{ value.split("/").reverse()[0] }}</a>
          </div>
        </div>
      </vs-col>
    </vs-row>
    <div class="badaso-upload-file__popup-dialog" tabindex="0" v-if="show">
      <div class="badaso-upload-file__popup-container">
        <div class="badaso-upload-file__popup--top-bar">
          <h3>{{ $t('fileManager.title') }}</h3>
          <vs-spacer />
          <vs-button color="danger" type="relief" class="badaso-upload-file__popup-button--delete" v-if="getSelected !== 'url' && isFileSelected" @click="openDialog">
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>
        <ul class="badaso-upload-file__popup--left-bar">
          <li :class="[getSelected === 'private'  ? 'active' : '' ]" @click="selected = 'private'" v-if="privateOnly || !privateOnly && !sharesOnly">
            Private
          </li>
          <li :class="[getSelected === 'shares' ? 'active' : '' ]" @click="selected = 'shares'" v-if="sharesOnly || !sharesOnly && !privateOnly">
            Shares
          </li>
        </ul>
        <div class="badaso-upload-file__popup--right-bar">
          <div class="badaso-upload-file__popup-add-file" @click="pickFile">
            <vs-icon icon="add" color="#06bbd3" size="75px"></vs-icon>
          </div>
          <div v-for="(item, index) in files.items" :key="index" @click="activeFile = index; isFileSelected = true">
            <div :class="[activeFile === index ? 'active' : '', 'badaso-upload-file__popup-file']" >
              <vs-icon icon="insert_drive_file" size="45px" color="#06bbd3"></vs-icon>
              <p class="badaso-upload-file__popup-file-text">{{ item.name }}</p>
            </div>
          </div>
        </div>
        <div class="badaso-upload-file__popup--bottom-bar">
          <div class="badaso-upload-file__popup-button--footer">
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
    <input type="file" class="badaso-upload-file__input--hidden" ref="file" @change="onFilePicked" />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span class="badaso-upload-file__input--error" v-for="(info, index) in alert" :key="index">
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-upload-file__input--error" v-html="alert"></span>
      </div>
    </div>
    <vs-popup :title="$t('action.delete.title')" :active.sync="dialog" class="badaso-upload-file__popup-dialog--delete">
      <p>{{ $t('action.delete.text') }}</p>
      <div class="badaso-upload-file__popup-dialog-content--delete">
        <vs-button color="primary" type="relief" @click="dialog = false">
          {{ $t('action.delete.cancel') }}
        </vs-button>
        <vs-button color="danger" type="relief" @click="deleteFile">
          {{ $t('action.delete.accept') }}
        </vs-button>
      </div>
    </vs-popup>
  </vs-col>
</template>

<script>
import * as _ from "lodash"
export default {
  name: "BadasoUploadFile",
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
      default: "Upload File",
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
      fileData: "",
      dialog: false,
      activeFile: 0,
      show: false,
      selected: 'private',
      files: {
        display: "",
        items: [],
        paginator: {},
      },
      files: [],
      isFileSelected: false,
    };
  },
  mounted() {
    if (this.sharesOnly) {
      this.selected = "shares"
    }

    this.fileData = this.value
  },
  computed: {
    getSelected() {
      this.activeFile = null
      return this.selected
    },
    getSelectedFolder() {
      if (this.getSelected === 'shares') return '/shares'
      else return this.getUserFolder
    },
    getUserFolder() {
      return '/' + this.$store.state.badaso.user.id
    },
    isSubmitDisable() {
      if (!this.isFileSelected) {
        return true
      }

      if (this.activeFile === null) {
        return true
      }

      return false
    }
  },
  watch: {
    selected: {
      handler(val) {
        this.getFiles()
        this.isFileSelected = false
      }
    },
    value: {
      handler(val) {
        this.fileData = val
      }
    }
  },
  methods: {
    pickFile() {
      this.$refs.file.click();
    },
    showOverlay() {
      this.show = true
      document.body.style.setProperty('position', 'fixed')
      document.body.style.setProperty("width", "100%");
      this.getFiles()
    },
    closeOverlay() {
      this.show = false
      document.body.style.removeProperty('position')
      document.body.style.removeProperty("width");
    },
    onFilePicked(e) {
      const files = e.target.files;
      if (files[0] !== undefined) {
        if (files[0].size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }

        this.files = files
        
        this.uploadFile()
      }
    },
    uploadFile() {
      const files = new FormData()
      files.append('upload', this.files[0])
      files.append('working_dir', this.getSelectedFolder)
      this.$api.badasoFile
        .uploadUsingLfm(files)
        .then(res => {
          let error = _.get(res, 'data.original.error', null)
          if (error) {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }
          this.getFiles()
        })
        .catch(error => {
          console.error(error);
        })
    },
    isString(str) {
      if (typeof str === "string" || str instanceof String) return true;
      else return false;
    },
    getFiles() {
      this.files.items = []
      if (this.getSelectedFolder) {
        this.$api.badasoFile
          .browseUsingLfm({
            workingDir: this.getSelectedFolder
          })
          .then(res => {
            let error = _.get(res, 'data.original.error', null)
            if (error) {
              this.$vs.notify({
                title: this.$t("alert.danger"),
                text: error.message,
                color: "danger",
              });
            }
            this.files = res.data
            this.files.items = res.data.items.filter(val => {
              return val.thumbUrl === null
            })
          })
          .catch(error => {
            console.log(error);
          })
      }
    },
    emitInput() {
      let url = this.files.items[this.activeFile].url
      this.$emit('input', url)
      this.closeOverlay()
    },
    deleteFile() {
      this.$api.badasoFile
        .deleteUsingLfm({
          workingDir: this.getSelectedFolder,
          'items[]': this.files.items[this.activeFile].name
        })
        .then(res => {
          let error = _.get(res, 'data.original.error', null)
          if (error) {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }
          this.getFiles()
        })
        .catch(error => {
          console.log(error);
        })

      this.activeFile = null
      this.dialog = false
    },
    openDialog() {
      this.dialog = true
    },
    deleteFilePicked(item) {
      if (item === null || item === undefined) return

      if (typeof item === 'string' && item !== '') this.$emit('input', null)
      if (typeof item === 'object') {
        this.selectedImageData = {}
        this.$emit('input', null)
      }
    }
  },
};
</script>
