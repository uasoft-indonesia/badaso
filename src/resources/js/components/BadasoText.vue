<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-input
      type="text"
      :label="displayLabel"
      :placeholder="placeholder"
      :disable="disabled"
      :readonly="readonly"
      :autofocus="autofocus"
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
      default: "Text",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String|Array,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Text",
    },
    value: {
      type: String,
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
  },
  computed: {
    displayLabel: function() {
      if (this.required) {
        return this.label + ' *'
      } else {
        return this.label
      }
    }
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>