<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <ul class="list-inline mb-0">
      <li
        class="mb-3 mb-lg-0 list-inline-item"
        v-for="item in items"
        :key="item.value"
      >
        <vs-checkbox
          class="justify-content-start"
          :value="value"
          @input="handleInput($event)"
          :vs-value="item.value"
          >{{ item.label }}</vs-checkbox
        >
      </li>
    </ul>
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
  name: "BadasoCheckbox",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Checkbox",
    },
    placeholder: {
      type: String,
      default: "Checkbox",
    },
    value: {
      type: Array,
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
      type: String|Array,
      default: "",
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>