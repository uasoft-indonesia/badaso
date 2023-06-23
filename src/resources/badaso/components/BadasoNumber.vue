<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-number__container">
    <vs-tooltip v-if="tooltip" :text="tooltip">
      <vs-icon icon="help_outline" size="16px" color="#A5A5A5" />
    </vs-tooltip>
    <vs-input
      type="number"
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
          class="badaso-number__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-number__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoNumber",
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
      default: "Number",
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
    tooltip: {
      type: String,
      default: null,
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
