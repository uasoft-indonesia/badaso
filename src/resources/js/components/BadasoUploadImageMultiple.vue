<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-on:keyup.space="pickFile"
      readonly
      v-model="imagesName"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12" v-for="(imageData, index) in value" :key="index">
        <div class="image-container" v-if="imageData.base64">
          <vs-button
            class="delete-image"
            color="danger"
            icon="close"
            @click="deleteFilePicked(imageData)"
          ></vs-button>
          <img :src="imageData.base64" class="image" />
        </div>
        <div class="image-container" v-else>
          <vs-button
            class="delete-image"
            color="danger"
            icon="close"
            @click="deleteStoredFile(imageData)"
          ></vs-button>
          <img
            :src="`${$api.file.view(imageData)}`"
            class="image"
          />
        </div>
      </vs-col>
    </vs-row>

    <input
      type="file"
      style="display: none"
      ref="image"
      accept="image/*"
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
  name: "BadasoUploadImageMultiple",
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Upload Image Multiple",
    },
    placeholder: {
      type: String,
      default: "Upload Image Multiple",
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
    imageBase64: function(val) {
      this.imageData.base64 = val;
    },
  },
  data() {
    return {
      imageData: {
        name: "",
        base64: "",
        file: {},
      },
      imageDatas: [],
      imagesName: "",
    };
  },
  methods: {
    pickFile() {
      this.$refs.image.click();
    },
    onFilePicked(e) {
      let files = e.target.files;
      let imageDatas = this.value;
      files.forEach((file) => {
        let image = {};
        if (file.size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }
        image.name = file.name;
        if (image.name.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(file);
        fr.addEventListener("load", () => {
          image.base64 = fr.result;
          image.file = file;
          this.value.push(image);
          let names = _.map(this.value, "name");
          let filtered = names.filter(function(el) {
            return el != null;
          });
          this.imagesName = filtered.join();
        });
      });

      setTimeout(() => {
        this.$emit("input", this.value);
      }, 500);
    },
    deleteFilePicked(e) {
      // let index = _.findIndex(this.imageDatas, e)
      // this.imageDatas.splice(index, 1);

      let indexValue = _.findIndex(this.value, e);
      this.value.splice(indexValue, 1);
      this.$emit("input", this.value);

      let names = _.map(this.value, "name");
      let filtered = names.filter(function(el) {
        return el != null;
      });
      this.imagesName = filtered.join();
    },
    deleteStoredFile(e) {
      let index = _.findIndex(this.value, e);
      this.value.splice(index, 1);
      this.$emit("input", this.value);
    },
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
}
.image {
  width: 100%;
}

.delete-image {
  opacity: 0;
  position: absolute;
  transition: all 0.2s ease;
}

.image-container:hover .delete-image {
  opacity: 1;
}
</style>
