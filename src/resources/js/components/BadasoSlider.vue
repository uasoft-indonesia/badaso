<template>
  <vs-col :vs-lg="size" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <vs-slider
      :min="min"
      :max="max"
      :value="value"
      @input="handleInput($event)"
    />
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