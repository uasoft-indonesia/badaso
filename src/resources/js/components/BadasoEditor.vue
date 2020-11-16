<template>
  <vs-col :vs-lg="size" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <vue-editor :value="value" @input="handleInput($event)"></vue-editor>
  </vs-col>
</template>

<script>
import { VueEditor } from "vue2-editor";

export default {
  name: "BadasoEditor",
  components: {
    VueEditor,
  },
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Editor",
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
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>