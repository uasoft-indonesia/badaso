<template>
  <vs-col :vs-lg="size" class="mb-3">
    <label for="" class="vs-input--label">{{label}}</label>
    <vs-switch :value="value" @input="handleInput($event)">
        <span slot="on">{{onLabel}}</span>
        <span slot="off">{{offLabel}}</span>
    </vs-switch>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSwitch",
  components: {},
  data: () => ({}),
  props: {
    size: {
        type: String,
        default: "12"
    },
    label: {
        type: String,
        default: "Switch"
    },
    placeholder: {
        type: String,
        default: "Switch"
    },
    onLabel: {
        type: String,
        default: "On"
    },
    offLabel: {
        type: String,
        default: "Off"
    },
    value:  {
        type: Boolean,
        required: true,
        default: false
    }
  },
  methods: {
      handleInput(val) {
          this.$emit('input', val)
      }
  }
};
</script>