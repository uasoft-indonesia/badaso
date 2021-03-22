<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <label for="" class="vs-input--label">{{label}}</label>
    <vs-switch :value="value" @input="handleInput($event)">
        <span slot="on">{{onLabel}}</span>
        <span slot="off">{{offLabel}}</span>
    </vs-switch>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p class="text-danger" v-for="(info, index) in alert" :key="index" v-html="info+'<br />'"></p>
      </div>
      <div v-else>
        <span class="text-danger" v-html="alert"></span>
      </div>
    </div>
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
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String|Array,
      default: "",
    },
  },
  methods: {
      handleInput(val) {
          this.$emit('input', val)
      }
  }
};
</script>