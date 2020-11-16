<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-model="imageData.name"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <div class="image-container"  v-if="imageData.base64">
        <vs-button class="delete-image" color="danger" icon="close" @click="deleteFilePicked(imageData)"></vs-button>
        <img :src="imageData.base64" class="image" />
    </div>
    <input
      type="file"
      style="display: none"
      ref="image"
      accept="image/*"
      @change="onFilePicked"
    />
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
      default: "Upload Image",
    },
    placeholder: {
      type: String,
      default: "Upload Image",
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
      imageData: {
        name: '',
        base64: '',
        file: {},
      },
    };
  },
  methods: {
    pickFile() {
      this.$refs.image.click();
    },
    onFilePicked(e) {
      const files = e.target.files;
      console.log(files)
      if (files[0] !== undefined) {
        if (files[0].size > 512000) {
          this.errorMessages = ["Out of limit size"];
          return;
        }
        let imageData = {}
        imageData.name = files[0].name;
        if (imageData.name.lastIndexOf(".") <= 0) {
          return;
        }
        const fr = new FileReader();
        fr.readAsDataURL(files[0]);
        fr.addEventListener("load", () => {
          imageData.base64 = fr.result;
          imageData.file = files[0];
          this.imageData = imageData
        });
      } else {
        this.imageData.name = "";
        this.imageData.file = "";
        this.imageData.base64 = "";
      }
      setTimeout(() => {
        this.$emit("input", this.imageData);
      }, 200);
    },
    deleteFilePicked(e) {
      this.imageData = {}
    }
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