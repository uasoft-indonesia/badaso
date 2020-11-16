<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-model="imagesName"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <div
      v-for="imageData in imageDatas"
      :key="imageData.base64"
      style="float: left"
    >
      <div class="image-container">
        <vs-button class="delete-image" color="danger" icon="close" @click="deleteFilePicked(imageData)"></vs-button>
        <img :src="imageData.base64" class="image" v-if="imageData.base64" />
      </div>
    </div>
    <input
      type="file"
      style="display: none"
      ref="image"
      accept="image/*"
      @change="onFilePicked"
      multiple
    />
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
      default: [],
    },
  },
  watch: {
    imageBase64: function (val) {
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
      let imageDatas = this.imageDatas;
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
          this.imageDatas.push(image);
          let names = _.map(this.imageDatas, "name");
        this.imagesName = names.join();
        });
      });

      setTimeout(() => {
        this.$emit("input", this.imageDatas);
      }, 500);
    },
    deleteFilePicked(e) {
      let index = _.findIndex(this.imageDatas, e)
      this.imageDatas.splice(index, 1);
      let names = _.map(this.imageDatas, "name");
      this.imagesName = names.join();
    },
  },
};
</script>

<style>
.image-container {
  max-width: 150px;
  border: solid 1px #dedede;
  box-shadow: 0 5px 20px 0 rgba(0, 0, 0, 0.1);
  margin: 10px;
}
.image {
    width: 100%
}

.delete-image {
    opacity: 0;
    position: absolute;
    transition: all .2s ease
}

.image-container:hover .delete-image {
    opacity: 1;
}
</style>