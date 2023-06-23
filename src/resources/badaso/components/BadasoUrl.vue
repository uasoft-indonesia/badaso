<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-url__container">
    <vs-input
      type="url"
      :label="label"
      :placeholder="placeholder"
      :value="value"
      icon="http"
      icon-after
      @input="handleInput($event)"
    />
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-url__input--error"
          >{{ info }}</span
        >
      </div>
      <div v-else>
        <span class="badaso-url__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoUrl",
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
      default: "URL",
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
