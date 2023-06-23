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
      />
      <div class="badaso-time__item-icon-box">
        <vs-icon icon="schedule" class="badaso-time__item-icon" />
      </div>
    </div>
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-time__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-time__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoTime",
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
  data: () => ({}),
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
