<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-time__container">
    <label v-if="label != ''" for="" class="badaso-time__label">{{
      label
    }}</label>
    <div class="badaso-time__time-container">
      <datetime
        :label="label"
        type="time"
        :title="label"
        :value="value"
        :value-zone="valueZone"
        :zone="zone"
        class="badaso-time__input"
        @input="handleInput($event)"
      ></datetime>
      <div class="badaso-time__item-icon-box">
        <vs-icon icon="schedule" class="badaso-time__item-icon"></vs-icon>
      </div>
    </div>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-time__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-time__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoTime",
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
      default: "Time",
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
