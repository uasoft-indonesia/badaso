<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-radio__container">
    <label v-if="label != ''" for="" class="badaso-radio__label">{{
      label
    }}</label>
    <ul class="badaso-radio__list">
      <li
        v-for="item in items"
        :key="item.value"
        class="badaso-radio__list-item"
      >
        <vs-radio
          :value="value"
          :vs-value="item.value"
          @input="handleInput($event)"
        >
          {{ item.label }}
        </vs-radio>
      </li>
    </ul>
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-radio__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-radio__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoRadio",
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
      default: "Radio",
    },
    value: {
      type: String,
      required: true,
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
  data: () => ({}),
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
