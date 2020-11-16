<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
        type="url"
        :label="label"
        :placeholder="placeholder"
        :value="value"
        @input="handleInput($event)"
    />
  </vs-col>
</template>

<script>
export default {
  name: "BadasoUrl",
  components: {},
  data: () => ({}),
  props: {
    size: {
        type: String,
        default: "12"
    },
    label: {
        type: String,
        default: "URL"
    },
    placeholder: {
        type: String,
        default: "URL"
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