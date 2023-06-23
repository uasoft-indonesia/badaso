<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-datetime__container">
    <label v-if="label != ''" for="" class="badaso-datetime__label">{{
      label
    }}</label>
    <div class="badaso-datetime__datetime-container">
      <datetime
        :label="label"
        type="datetime"
        :title="label"
        :value="value"
        :value-zone="valueZone"
        :zone="zone"
        class="badaso-datetime__input"
        @input="handleInput($event)"
      ></datetime>
      <div class="badaso-datetime__datetime-icon-box">
        <vs-icon
          icon="date_range"
          class="badaso-datetime__datetime-icon"
        ></vs-icon>
      </div>
    </div>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-datetime__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-datetime__input--error" v-html="alert"></span>
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
      type: String || Array,
      default: "",
    },
    valueZone: {
      type: String,
      default: "local",
    },
    zone: {
      type: String,
      default: "local",
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
