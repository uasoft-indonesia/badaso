<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-model="filesName"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <div
      v-for="fileData in fileDatas"
      :key="fileData.base64"
      style="float: left"
    >
      <div class="file-container">
        <vs-button class="delete-file" color="danger" icon="close" @click="deleteFilePicked(fileData)"></vs-button>
        <div class="file">
            {{fileData.name}}
        </div>
      </div>
    </div>
    <input
      type="file"
      style="display: none"
      ref="file"
      @change="onFilePicked"
      multiple
    />
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
      default: [],
    },
  },
  watch: {
    fileBase64: function (val) {
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
          this.fileDatas.push(newfile);
          let names = _.map(this.fileDatas, "name");
        this.filesName = names.join();
        });
      });

      setTimeout(() => {
        this.$emit("input", this.fileDatas);
      }, 500);
    },
    deleteFilePicked(e) {
      let index = _.findIndex(this.fileDatas, e)
      this.fileDatas.splice(index, 1);
      let names = _.map(this.fileDatas, "name");
      this.filesName = names.join();
    },
  },
};
</script>
<style>
.file-container {
  height: 150px;
  width: 150px;
  border: solid 1px #dedede;
  box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.1);
  margin: 10px;
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
    transition: all .2s ease
}

.file-container:hover .delete-file {
    opacity: 1;
}
</style>