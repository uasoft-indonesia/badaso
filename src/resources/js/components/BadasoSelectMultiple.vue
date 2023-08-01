<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-select-multiple__container">
    <vs-select
      :label="label"
      :placeholder="placeholder"
      :value="value"
      @input="handleInput($event)"
      width="100%"
      multiple
      autocomplete
    >
      <vs-select-item
        :key="index"
        :value="satinize(item.value)"
        :text="satinize(item.label)"
        v-for="(item, index) in items"
      />
    </vs-select>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-select-multiple__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span
          class="badaso-select-multiple__input--error"
          v-html="alert"
        ></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
import * as DOMPurify from 'dompurify';
export default {
  name: "BadasoSelectMultiple",
  components: {},
  data: () => ({}),
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
      default: "Select Multiple",
    },
    value: {
      type: Array,
      default: () => {
        return [];
      },
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
      type: String || Array,
      default: "",
    },
  },
  methods: {
    satinize(item) {
      return DOMPurify.sanitize(item)
    },
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
