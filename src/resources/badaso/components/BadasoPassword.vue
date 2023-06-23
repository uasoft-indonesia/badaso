<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-password__container">
    <vs-input
      type="password"
      :label="label"
      :placeholder="placeholder"
      :value="value"
      @input="handleInput($event)"
    />
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-password__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-password__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoPassword",
  components: {},
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Password",
    },
    value: {
      type: String,
      required: true,
      default: "",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String || Array,
      default: "",
    },
  },
  data: () => ({}),
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
