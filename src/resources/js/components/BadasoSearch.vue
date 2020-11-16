<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
        type="search"
        :label="label"
        :placeholder="placeholder"
        :value="value"
        @input="handleInput($event)"
    />
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSearch",
  components: {},
  data: () => ({}),
  props: {
    size: {
        type: String,
        default: "12"
    },
    label: {
        type: String,
        default: "Search"
    },
    placeholder: {
        type: String,
        default: "Search"
    },
    value:  {
        type: String,
        required: true,
        default: ''
    }
  },
  methods: {
      handleInput(val) {
          this.$emit('input', val)
      }
  }
};
</script>