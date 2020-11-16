<template>
  <vs-col :vs-lg="size" class="mb-3">
    <!-- <vs-input
        type="time"
        :label="label"
        :value="value"
        @input="handleInput($event)"
    /> -->
    <label for="" class="vs-input--label">{{ label }}</label>
    <datetime
        :label="label"
        type="datetime"
        :title="label"
        :value="value"
        @input="handleInput($event)"></datetime>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoDatetime",
  components: {},
  data: () => ({}),
  props: {
    size: {
        type: String,
        default: "12"
    },
    label: {
        type: String,
        default: "Date Time"
    },
    placeholder: {
        type: String,
        default: "Date Time"
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
<style>
    .vdatetime-input {
        width: 100%;
        padding: .4rem;
        border: 1px solid rgba(0, 0, 0, 0.2);   
    }
</style>