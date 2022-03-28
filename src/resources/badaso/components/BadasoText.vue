<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-text__container">
    <label class="badaso-text__label"
      >{{ displayLabel }}
      <vs-tooltip :text="tooltip" v-if="tooltip">
        <vs-icon icon="help_outline" size="16px" color="#A5A5A5"></vs-icon>
      </vs-tooltip>
    </label>
    <vs-input
      type="text"
      :placeholder="placeholder"
      :disabled="disabled"
      :readonly="readonly"
      :autofocus="autofocus"
      :value="value"
      @input="handleInput($event)"
    />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-text__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-text__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoText",
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
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String || Array,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Text",
    },
    value: {
      required: true,
      default: "",
    },
    readonly: {
      type: Boolean,
      default: false,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    autofocus: {
      type: Boolean,
      default: false,
    },
    required: {
      type: Boolean,
      default: false,
    },
    tooltip: {
      type: String,
      default: null,
    },
  },
  computed: {
    displayLabel: function () {
      if (this.required) {
        return this.label + " *";
      } else {
        return this.label;
      }
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
