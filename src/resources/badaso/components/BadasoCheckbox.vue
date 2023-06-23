<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-checkbox__container">
    <label v-if="label != ''" for="" class="badaso-checkbox__label">{{
      label
    }}</label>
    <ul class="badaso-checkbox__list">
      <li
        v-for="item in items"
        :key="item.value"
        class="badaso-checkbox__list-item"
      >
        <vs-checkbox
          class="badaso-checkbox__checkbox"
          :value="value"
          :vs-value="item.value"
          @input="handleInput($event)"
        >
          {{ item.label }}
        </vs-checkbox>
      </li>
    </ul>
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-checkbox__checkbox--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-checkbox__checkbox--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoCheckbox",
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
      default: "Checkbox",
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
  data: () => ({}),
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
