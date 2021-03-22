<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
      :label="label"
      :placeholder="placeholder"
      @click="pickFile"
      v-on:keyup.space="pickFile"
      readonly
      v-model="imageData.name"
      icon="attach_file"
      icon-after="true"
    ></vs-input>
    <vs-row>
      <vs-col vs-lg="4" vs-sm="12">
        <div class="image-container"  v-if="imageData.base64">
        <vs-button class="delete-image" color="danger" icon="close" @click="deleteFilePicked(imageData)"></vs-button>
        <img :src="imageData.base64" class="image" />
    </div>
    <div class="image-container" v-else-if="isString(value) && value !== ''">
        <vs-button class="delete-image" color="danger" icon="close" @click="deleteStoredFile(value)"></vs-button>
        <img :src="`${$api.file.view(value)}`" class="image" />
    </div>
      </vs-col>
    </vs-row>
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
      type: Object|String,
      default: () => {
        return null;
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
      this.$refs.image.tabindex = -1;
      const files = e.target.files;
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
    },
    deleteStoredFile(e) {
      this.$emit("input", null);
    },
    isString(str) {
      if (typeof str === 'string' || str instanceof String)
        return true
      else
        return false
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