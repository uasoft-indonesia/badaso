<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <label v-if="label != ''" for="" class="vs-input--label">{{ label }}</label>
    <div class="datetime-box">
      <datetime
        :label="label"
        type="datetime"
        :title="label"
        :value="value"
        class="datetime-input"
        @input="handleInput($event)"
      ></datetime>
      <div class="datetime-icon-box">
      <vs-icon icon="date_range" class="datetime-icon"></vs-icon>
      </div>
    </div>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p
          class="text-danger"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        ></p>
      </div>
      <div v-else>
        <span class="text-danger" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoDatetime",
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
      default: "Date Time",
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
      type: String | Array,
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
