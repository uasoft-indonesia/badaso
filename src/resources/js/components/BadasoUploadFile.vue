<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-model="fileData.name"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12">
        <div class="file-container" v-if="fileData.base64">
          <vs-button
            class="delete-file"
            color="danger"
            icon="close"
            @click="deleteFilePicked(fileData)"
          ></vs-button>
          <div class="file">
            {{ fileData.name }}
          </div>
        </div>
        <div class="file-container" v-else-if="isString(value)">
          <vs-button
            class="delete-file"
            color="danger"
            icon="close"
            @click="deleteStoredFile(value)"
          ></vs-button>
          <div class="file">
            <a
              target="_blank"
              :href="`${$api.file.download(value)}`"
              >{{ value.split("/").reverse()[0] }}</a
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
      type: Object | String,
      default: () => {
        return {};
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
  data() {
    return {
      fileData: {
        name: "",
        base64: "",
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
      if (files[0] !== undefined) {
        if (files[0].size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }
        let fileData = {};
        fileData.name = files[0].name;
        if (fileData.name.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          fileData.base64 = fr.result;
          fileData.file = files[0];
          this.fileData = fileData;
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
      this.fileData = {};
    },
    deleteStoredFile(e) {
      this.$emit("input", null);
    },
    isString(str) {
      if (typeof str === "string" || str instanceof String) return true;
      else return false;
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
