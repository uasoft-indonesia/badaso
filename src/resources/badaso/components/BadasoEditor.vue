<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-editor__container">
    <label v-if="label != ''" for="" class="badaso-editor__label">{{
      label
    }}</label>
    <editor
      id="tinymce"
      :value="value"
      @input="handleInput($event)"
      :init="init"
    ></editor>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p
          class="badaso-editor__input--error"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        ></p>
      </div>
      <div v-else>
        <span class="badaso-editor__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
// eslint-disable-next-line no-unused-vars
import tinymce from "tinymce";
import TinyMCE from "@tinymce/tinymce-vue";

import "tinymce/themes/silver/theme";
import "tinymce/themes/mobile/theme";
import "tinymce/icons/default/icons";

import "tinymce/plugins/advlist";
import "tinymce/plugins/anchor";
import "tinymce/plugins/contextmenu";
import "tinymce/plugins/directionality";
import "tinymce/plugins/emoticons";
import "tinymce/plugins/fullpage";
import "tinymce/plugins/fullscreen";
import "tinymce/plugins/help";
import "tinymce/plugins/hr";
import "tinymce/plugins/image";
import "tinymce/plugins/imagetools";
import "tinymce/plugins/importcss";
import "tinymce/plugins/autolink";
import "tinymce/plugins/insertdatetime";
import "tinymce/plugins/legacyoutput";
import "tinymce/plugins/link";
import "tinymce/plugins/lists";
import "tinymce/plugins/media";
import "tinymce/plugins/nonbreaking";
import "tinymce/plugins/noneditable";
import "tinymce/plugins/pagebreak";
import "tinymce/plugins/paste";
import "tinymce/plugins/preview";
import "tinymce/plugins/autoresize";
import "tinymce/plugins/print";
import "tinymce/plugins/quickbars";
import "tinymce/plugins/save";
import "tinymce/plugins/searchreplace";
import "tinymce/plugins/spellchecker";
import "tinymce/plugins/tabfocus";
import "tinymce/plugins/table";
import "tinymce/plugins/template";
import "tinymce/plugins/textcolor";
import "tinymce/plugins/textpattern";
import "tinymce/plugins/autosave";
import "tinymce/plugins/toc";
import "tinymce/plugins/visualblocks";
import "tinymce/plugins/visualchars";
import "tinymce/plugins/wordcount";
import "tinymce/plugins/bbcode";
import "tinymce/plugins/charmap";
import "tinymce/plugins/code";
import "tinymce/plugins/codesample";
import "tinymce/plugins/colorpicker";
import "tinymce/plugins/emoticons/js/emojis";

export default {
  name: "BadasoEditor",
  components: {
    editor: TinyMCE,
  },
  data() {
    return {
      init: {
        height: 500,
        plugins: [
          "lists advlist",
          "image imagetools",
          "link autolink",
          "table",
          "charmap",
          "searchreplace visualblocks code fullscreen",
          "print preview anchor insertdatetime media",
          "help codesample hr pagebreak nonbreaking toc textpattern noneditable ",
          "importcss",
          "directionality",
          "visualchars",
          "emoticons",
          "autosave",
        ],
        toolbar: [
          "undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect",
          "alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | preview save print | insertfile image media template link anchor codesample | ltr rtl",
        ],
        menubar: true,
        convert_urls: false,
        images_upload_handler: (blobInfo, success, failure) => {
          const files = new FormData();
          files.append("upload", blobInfo.blob());
          files.append("working_dir", "/shares");

          this.$openLoader();
          this.$api.badasoFile
            .uploadUsingLfm(files)
            .then((response) => {
              this.$closeLoader();
              if (response.data.original.url) {
                success(response.data.original.url);
              }

              if (response.data.original.error) {
                failure(response.data.original.error.message);
              }
            })
            .catch((error) => {
              failure(error);
              this.$closeLoader();
              this.$vs.notify({
                title: this.$t("alert.danger"),
                text: error.message,
                color: "danger",
              });
            });
        },
      },
    };
  },
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
      default: "Editor",
    },
    value: {
      type: String,
      required: true,
      default: "",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String || Array,
      default: "",
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
