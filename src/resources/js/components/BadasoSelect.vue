<template>
  <vs-col vs-lg="6" class="mb-3">
    <vs-select
      :label="label"
      :placeholder="placeholder"
      :value="value"
      @input="handleInput($event)"
      width="100%"
    >
      <vs-select-item
        :key="index"
        :value="item.value"
        :text="item.label"
        v-for="(item, index) in items"
      />
    </vs-select>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSelect",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Select",
    },
    placeholder: {
      type: String,
      default: "Select",
    },
    value: {
      type: String,
      required: true,
      default: "",
    },
    items: {
      type: Array,
      required: true,
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>