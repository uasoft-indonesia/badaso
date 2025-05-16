<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-select__container">
  <vs-select
  :label="label"
  :placeholder="placeholder"
  :modelValue="modelValue"
  @update:modelValue="$emit('update:modelValue', $event)"
  width="100%"
  @change="handleChange($event)"
>
      <vs-select-item
        v-for="(item, index) in items"
        :key="index"
        :value="satinize(item.value || item)"
        :text="satinize(item.label || item)"
      />
    </vs-select>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="Array.isArray(alert)">
        <span
          class="badaso-select__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-select__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
import DOMPurify from 'dompurify';
export default {
  name: "BadasoSelect",
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
      default: "Select",
    },
    modelValue: {
      type: [String, Number, null],
      default: "",
    },
    items: {
      type: Array,
      required: true,
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
  emits: ["update:modelValue", "change"],
  methods: {
    satinize(item) {
      return item ? DOMPurify.sanitize(item) : "";
    },
    handleInput(val) {
      this.$emit("update:modelValue", val);
    },
    handleChange(val) {
      this.$emit("change", val);
    },
  },
};
</script>
