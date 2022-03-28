<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-slider__container">
    <label v-if="label != ''" for="" class="badaso-slider__label">{{
      label
    }}</label>
    <vs-slider
      class="badaso-slider__input"
      :min="min"
      :max="max"
      :value="value"
      @input="handleInput($event)"
    />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-slider__input--error"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        ></span>
      </div>
      <div v-else>
        <span class="badaso-slider__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSlider",
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
      default: "Slider",
    },
    min: {
      type: Number,
      default: 0,
    },
    max: {
      type: Number,
      default: 100,
    },
    value: {
      type: Number,
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
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
