<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-model="fileData.name"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <div class="file-container"  v-if="fileData.base64">
        <vs-button class="delete-file" color="danger" icon="close" @click="deleteFilePicked(fileData)"></vs-button>
        <div class="file">
            {{fileData.name}}
        </div>
    </div>
    <input
      type="file"
      style="display: none"
      ref="file"
      @change="onFilePicked"
    />
  </vs-col>
</template>

<script>
export default {
  name: "BadasoUploadFile",
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Upload File",
    },
    placeholder: {
      type: String,
      default: "Upload File",
    },
    value: {
      type: Object,
      default: () => {
        return {};
      },
    },
  },
  data() {
    return {
      fileData: {
        name: '',
        base64: '',
        file: {},
      },
    };
  },
  methods: {
    pickFile() {
      this.$refs.file.click();
    },
    onFilePicked(e) {
      const files = e.target.files;
      console.log(files)
      if (files[0] !== undefined) {
        if (files[0].size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }
        let fileData = {}
        fileData.name = files[0].name;
        if (fileData.name.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          fileData.base64 = fr.result;
          fileData.file = files[0];
          this.fileData = fileData
        });
      } else {
        this.fileData.name = "";
        this.fileData.file = "";
        this.fileData.base64 = "";
      }
      setTimeout(() => {
        this.$emit("input", this.fileData);
      }, 200);
    },
    deleteFilePicked(e) {
      this.fileData = {}
    }
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