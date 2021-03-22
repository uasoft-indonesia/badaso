<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <vs-slider
      :min="min"
      :max="max"
      :value="value"
      @input="handleInput($event)"
    />
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
      default: "Slider",
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

<style>
.con-vs-slider {
  z-index: 1;  
}
.vs-slider--circle {
  z-index : 2;
}
</style>