<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-textarea__container">
    <label v-if="label != ''" for="" class="badaso-textarea__label">{{
      label
    }}</label>
    <vs-textarea :value="value" @input="handleInput($event)" />
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-textarea__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-textarea__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoTextarea",
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
      default: "Textarea",
    },
    value: {
      required: true,
      default: "null",
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
