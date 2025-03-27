<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-textarea__container">
    <label v-if="label != ''" for="" class="badaso-textarea__label">{{
      label
    }}</label>
    <vs-textarea v-model="localValue" />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-textarea__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-textarea__input--error" v-html="alert"></span>
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
    modelValue: {
      type: String,
      required: true,
      default: "",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: [String, Array],
      default: "",
    },
  },
  data() {
    return {
      localValue: this.modelValue,
    };
  },
  watch: {
    modelValue(newValue) {
      this.localValue = newValue;
    },
    localValue(newValue) {
      this.$emit('update:modelValue', newValue);
    },
  },
};
</script>
