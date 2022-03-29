<template>
  <div>
    <vs-col
      v-if="size !== ''"
      :vs-lg="size"
      vs-xs="12"
      class="badaso-code-editor__container"
    >
      <label v-if="label != ''" for="" class="badaso-code-editor__label">{{
        label
      }}</label>
      <prism-editor
        class="badaso-code-editor__editor"
        :value="value"
        @input="handleInput($event)"
        line-numbers
        :highlight="highlighter"
      ></prism-editor>
      <div v-if="additionalInfo" v-html="additionalInfo"></div>
      <div v-if="alert">
        <div v-if="$helper.isArray(alert)">
          <span
            class="badaso-code-editor__editor--error"
            v-for="(info, index) in alert"
            :key="index"
            >{{ info }}</span
          >
        </div>
        <div v-else>
          <span class="badaso-code-editor__editor--error" v-html="alert"></span>
        </div>
      </div>
    </vs-col>
    <div v-else>
      <label v-if="label != ''" for="" class="badaso-code-editor__label">{{
        label
      }}</label>
      <prism-editor
        class="badaso-code-editor__editor"
        :value="value"
        @input="handleInput($event)"
        line-numbers
        :highlight="highlighter"
      ></prism-editor>
      <div v-if="additionalInfo" v-html="additionalInfo"></div>
      <div v-if="alert">
        <div v-if="$helper.isArray(alert)">
          <span
            class="badaso-code-editor__editor--error"
            v-for="(info, index) in alert"
            :key="index"
          >
            {{ info }}
          </span>
        </div>
        <div v-else>
          <span class="badaso-code-editor__editor--error" v-html="alert"></span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
// import Prism Editor
import { PrismEditor } from "vue-prism-editor";

// import highlighting library (you can use any library you want just return html string)
import { highlight, languages } from "prismjs/components/prism-core";
import "prismjs/components/prism-clike";
import "prismjs/components/prism-javascript";

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
      type: String || Array,
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
