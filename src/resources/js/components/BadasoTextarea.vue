<template>
  <vs-col :vs-lg="size" class="mb-3">
    <label for="" class="vs-input--label">{{label}}</label>
    <vs-textarea :value="value" @input="handleInput($event)" />
  </vs-col>
</template>

<script>
export default {
  name: "BadasoTextarea",
  components: {},
  data: () => ({}),
  props: {
    size: {
        type: String,
        default: "12"
    },
    label: {
        type: String,
        default: "Textarea"
    },
    placeholder: {
        type: String,
        default: "Textarea"
    },
    value:  {
        type: String,
        required: true,
        default: 'null'
    }
  },
  methods: {
      handleInput(val) {
          this.$emit('input', val)
      }
  }
};
</script>