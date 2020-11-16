<template>
  <vs-col :vs-lg="size" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <ul class="list-inline mb-0">
      <li
        class="mb-3 mb-lg-0 list-inline-item"
        v-for="item in items"
        :key="item.value"
      >
        <vs-radio
          :value="value"
          @input="handleInput($event)"
          :vs-value="item.value"
          >{{ item.label }}</vs-radio
        >
      </li>
    </ul>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoRadio",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Radio",
    },
    placeholder: {
      type: String,
      default: "Radio",
    },
    value: {
      type: String,
      required: true,
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