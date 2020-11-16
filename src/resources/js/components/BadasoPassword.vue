<template>
  <vs-col :vs-lg="size" class="mb-3">
    <vs-input
        type="password"
        :label="label"
        :placeholder="placeholder"
        :value="value"
        @input="handleInput($event)"
    />
  </vs-col>
</template>

<script>
export default {
  name: "BadasoPassword",
  components: {},
  data: () => ({}),
  props: {
    size: {
        type: String,
        default: "12"
    },
    label: {
        type: String,
        default: "Password"
    },
    placeholder: {
        type: String,
        default: "Password"
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