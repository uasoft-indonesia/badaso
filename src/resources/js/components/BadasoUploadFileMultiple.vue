<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-upload-file-multiple__container">
    <vs-input :label="label" :placeholder="placeholder" @click="showOverlay" v-on:keyup.space="showOverlay" readonly v-model="filesName" icon="attach_file" icon-after="true" />
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12" v-for="(fileData, index) in value" :key="index">
        <div class="badaso-upload-file-multiple__preview">
          <vs-button class="badaso-upload-file-multiple__remove-button" color="danger" icon="close" @click="deleteFilePicked(fileData)" />
          <div class="badaso-upload-file-multiple__preview-text">
            <a target="_blank" :href="`${$api.badasoFile.download(fileData)}`" >{{ fileData.split("/").reverse()[0] }}</a>
          </div>
        </div>
      </vs-col>
    </vs-row>
    <div class="badaso-upload-file-multiple__popup-dialog" tabindex="0" v-if="show">
      <div class="badaso-upload-file-multiple__popup-container">
        <div class="badaso-upload-file-multiple__popup--top-bar">
          <h3>{{ $t('fileManager.title') }}</h3>
          <vs-spacer />
          <vs-button color="danger" type="relief" class="badaso-upload-file-multiple__popup-button--delete" v-if="getSelected !== 'url' && isFileSelected" @click="openDialog">
            <vs-icon icon="delete"></vs-icon>
          </vs-button>
        </div>
        <ul class="badaso-upload-file-multiple__popup--left-bar">
          <li :class="[getSelected === 'private'  ? 'active' : '' ]" @click="selected = 'private'" v-if="privateOnly || !privateOnly && !sharesOnly">
            Private
          </li>
          <li :class="[getSelected === 'shares' ? 'active' : '' ]" @click="selected = 'shares'" v-if="sharesOnly || !sharesOnly && !privateOnly">
            Shares
          </li>
        </ul>
        <div class="badaso-upload-file-multiple__popup--right-bar">
          <div class="badaso-upload-file-multiple__popup-add-image" @click="pickFile">
            <vs-icon icon="add" color="#06bbd3" size="75px"></vs-icon>
          </div>
          <div :class="[activeFile.includes(index) ? 'active' : '', 'badaso-upload-file-multiple__popup-file']" v-for="(item, index) in files.items" :key="index" @click="selectFile(index)">
            <vs-icon icon="insert_drive_file" size="45px" color="#06bbd3"></vs-icon>
            <p class="badaso-upload-file-multiple__popup-file-text">{{ item.name }}</p>
          </div>
        </div>
        <div class="badaso-upload-file-multiple__popup--bottom-bar">
          <div class="badaso-upload-file-multiple__popup-button--footer">
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
    <input type="file" class="badaso-upload-file-multiple__input--hidden" ref="file" @change="onFilePicked" multiple />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span class="badaso-upload-file-multiple__input--error" v-for="(info, index) in alert" :key="index" >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-upload-file-multiple__input--error" v-html="alert"></span>
      </div>
    </div>
    <vs-popup :title="$t('action.delete.title')" :active.sync="dialog" class="badaso-upload-file-multiple__popup-dialog--delete">
      <p>{{ $t('action.delete.text') }}</p>
      <div class="badaso-upload-file-multiple__popup-dialog-content--delete">
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
import _ from "lodash";

export default {
  name: "BadasoUploadFileMultiple",
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
      default: "Upload File Multiple",
    },
    value: {
      type: Array,
      default: () => {
        return [];
      },
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
  watch: {
    selected: {
      handler(val) {
        this.getFiles()
        this.isFileSelected = false
      }
    },
    value: {
      handler(val) {
        this.fileDatas = val
      }
    }
  },
  mounted() {
    if (this.sharesOnly) {
      this.selected = "shares"
    }

    this.fileDatas = this.value
    this.filesName = this.fileDatas.join(', ')
  },
  data() {
    return {
      fileDatas: [],
      filesName: "",
      dialog: false,
      activeFile: [],
      show: false,
      selected: 'private',
      files: {
        display: "",
        items: [],
        paginator: {},
      },
      files: [],
      isFileSelected: false,
      dirty: false
    };
  },
  computed: {
    getSelected() {
      this.activeFile = []
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

      if (this.activeFile.length === 0) {
        return true
      }

      return false
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
      let files = e.target.files;
      files.forEach((file) => {
        if (file.size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }
        this.files = file
        this.uploadFile()
      });
    },
    uploadFile() {
      const files = new FormData()
      files.append('upload', this.files)
      files.append('working_dir', this.getSelectedFolder)
      this.$api.badasoFile
        .uploadUsingLfm(files)
        .then(res => {
          this.getFiles()
        })
        .catch(error => {
          console.error(error);
        })
    },
    getFiles() {
      this.files.items = []
      if (this.getSelectedFolder) {
        this.$api.badasoFile
          .browseUsingLfm({
            workingDir: this.getSelectedFolder
          })
          .then(res => {
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
      let url = []
      this.activeFile.forEach(element => {
        url.push(this.files.items[element].url)
      });
      this.filesName = url.join(', ')
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
          this.getFiles()
        })
        .catch(error => {
          console.log(error);
        })

      this.activeFile = []
      this.dialog = false
    },
    openDialog() {
      this.dialog = true
    },
    selectFile(index) {
      if (!this.activeFile.includes(index)) {
        this.activeFile.push(index)
      } else {
        let idx = this.activeFile.indexOf(index)
        this.activeFile.splice(idx, 1)
      }

      this.isFileSelected = true
    },
    deleteFilePicked(item) {
      if (item === null || item === undefined) return

      if (typeof item === 'string' && item !== '') {
        let idx = this.fileDatas.indexOf(item)
        let activeIdx = this.activeFile.indexOf(idx)
        this.activeFile.splice(activeIdx, 1)
        this.fileDatas.splice(idx, 1)
        this.filesName = this.fileDatas.join(', ')
        this.$emit('input', this.fileDatas)
      }
    }
  },
};
</script>
