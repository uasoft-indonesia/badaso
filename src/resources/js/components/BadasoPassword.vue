<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
        type="password"
        :label="label"
        :placeholder="placeholder"
        :value="value"
        @input="handleInput($event)"
    />
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