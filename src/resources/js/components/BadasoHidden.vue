<template>
    <vs-input
      type="hidden"
      :value="value"
      @input="handleInput($event)"
    />
</template>

<script>
export default {
  name: "BadasoHidden",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Text",
    },
    placeholder: {
      type: String,
      default: "Text",
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