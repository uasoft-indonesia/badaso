<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-model="filesName"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <vs-row>
    <vs-col vs-lg="4" vs-sm="12" v-for="(fileData, index) in value" :key="index" style="float: left">
      <div class="file-container">
        <vs-button
          class="delete-file"
          color="danger"
          icon="close"
          @click="deleteFilePicked(fileData)"
        ></vs-button>
        <div class="file">
          <a
            v-if="fileData.name"
            target="_blank"
            :href="`${$api.file.download(fileData.name)}`"
            >{{ fileData.name }}</a
          >
          <a
            v-else
            target="_blank"
            :href="`${$api.file.download(fileData)}`"
            >{{ fileData.split('/').reverse()[0] }}</a
          >
        </div>
      </div>
    </vs-col>
    </vs-row>
    <input
      type="file"
      style="display: none"
      ref="file"
      @change="onFilePicked"
      multiple
    />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p class="text-danger" v-for="(info, index) in alert" :key="index" v-html="info+'<br />'"></p>
      </div>
      <div v-else>
        <span class="text-danger" v-html="alert"></span>
      </div>
    </div>
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
      default: "Upload File Multiple",
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
      type: String|Array,
      default: "",
    },
  },
  watch: {
    fileBase64: function(val) {
      this.fileData.base64 = val;
    },
  },
  data() {
    return {
      fileData: {
        name: "",
        base64: "",
        file: {},
      },
      fileDatas: [],
      filesName: "",
    };
  },
  methods: {
    pickFile() {
      this.$refs.file.click();
    },
    onFilePicked(e) {
      let files = e.target.files;
      let fileDatas = this.fileDatas;
      files.forEach((file) => {
        let newfile = {};
        if (file.size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }
        newfile.name = file.name;
        if (newfile.name.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(file);
        fr.addEventListener("load", () => {
          newfile.base64 = fr.result;
          newfile.file = file;
          this.value.push(newfile);
          let names = _.map(this.value, "name");
          let filtered = names.filter(function(el) {
            return el != null;
          });
          this.filesName = filtered.join();
        });
      });

      setTimeout(() => {
        this.$emit("input", this.value);
      }, 500);
    },
    deleteFilePicked(e) {
      let index = _.findIndex(this.value, e);
      this.value.splice(index, 1);
      let names = _.map(this.value, "name");
      let filtered = names.filter(function(el) {
        return el != null;
      });
      this.filesName = filtered.join();
    },
  },
};
</script>
<style>
.file-container {
  width: 100%;
  height: 150px;
  border: solid 1px #dedede;
  box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.1);
  margin-top: 10px;
  overflow: hidden;
  position: relative;
}

.file {
  margin: 0;
  position: absolute;
  top: 50%;
  -ms-transform: translateY(-50%);
  transform: translateY(-50%);
  text-align: center;
  width: 100%;
}

.delete-file {
  opacity: 0;
  position: absolute;
  transition: all 0.2s ease;
}

.file-container:hover .delete-file {
  opacity: 1;
}
</style>
