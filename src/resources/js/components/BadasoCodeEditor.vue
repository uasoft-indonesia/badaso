<template>
  <div>
    <vs-col v-if="size !== ''" :vs-lg="size"  vs-xs="12" class="mb-3">
      <label for="" class="vs-input--label">{{ label }}</label>
      <prism-editor
        class="my-editor"
        :value="value"
        @input="handleInput($event)"
        line-numbers
        :highlight="highlighter"
      ></prism-editor>
      <div v-if="additionalInfo" v-html="additionalInfo"></div>
      <div v-if="alert">
        <div v-if="$helper.isArray(alert)">
          <p
            class="text-danger"
            v-for="(info, index) in alert"
            :key="index"
            v-html="info+'<br />'"
          ></p>
        </div>
        <div v-else>
          <span class="text-danger" v-html="alert"></span>
        </div>
      </div>
    </vs-col>
    <div v-else>
      <prism-editor
        class="my-editor"
        :value="value"
        @input="handleInput($event)"
        line-numbers
        :highlight="highlighter"
      ></prism-editor>
      <div v-if="additionalInfo" v-html="additionalInfo"></div>
      <div v-if="alert">
        <div v-if="$helper.isArray(alert)">
          <span
            class="text-danger"
            v-for="(info, index) in alert"
            :key="index"
            v-html="info+'<br />'"
          ></span>
        </div>
        <div v-else>
          <span class="text-danger" v-html="alert"></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import Prism Editor
import { PrismEditor } from "vue-prism-editor";
import "vue-prism-editor/dist/prismeditor.min.css"; // import the styles somewhere

// import highlighting library (you can use any library you want just return html string)
import { highlight, languages } from "prismjs/components/prism-core";
import "prismjs/components/prism-clike";
import "prismjs/components/prism-javascript";
import "prismjs/themes/prism-tomorrow.css"; // import syntax highlighting styles

export default {
  name: "BadasoCodeEditor",
  components: {
    PrismEditor,
  },
  props: {
    value: {
      type: String,
      required: true,
      default: "",
    },
    size: {
      type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String | Array,
      default: "",
    },
  },
  data: () => ({}),
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
    highlighter(code) {
      return highlight(code, languages.js); // languages.<insert language> to return html with markup
    },
  },
};
</script>

<style>
/* required class */
.my-editor {
  /* we dont use `language-` classes anymore so thats why we need to add background and text color manually */
  background: #fff;
  color: #000;

  /* you must provide font-family font-size line-height. Example: */
  font-family: Fira code, Fira Mono, Consolas, Menlo, Courier, monospace;
  font-size: 14px;
  line-height: 1.5;
  padding: 5px;
  border: solid 1px #dedede;
  height: 100px;
}

/* optional class for removing the outline */
.prism-editor__textarea:focus {
  outline: none;
}
.prism-editor__line-numbers {
  background-color: #e8e8e8 !important;
}
</style>
