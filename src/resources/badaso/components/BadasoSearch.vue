<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-search__container">
    <vs-input
      type="search"
      :label="label"
      :placeholder="placeholder"
      :value="value"
      icon="search"
      icon-after
      @input="handleInput($event)"
    />
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-search__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-search__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSearch",
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
      default: "Search",
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
