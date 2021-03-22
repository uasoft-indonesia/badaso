<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <div class="input-group color-picker" ref="colorpicker">
      <input
        type="text"
        class="form-control"
        :value="colorValue"
        @focus="showPicker()"
        @input="updateFromInput"
      />
      <span class="input-group-addon color-picker-container">
        <span
          class="current-color"
          :style="'background-color: ' + colorValue"
          @click="togglePicker()"
        ></span>
      </span>
      <color-picker
        :value="colorValue"
        @input="updateFromPicker"
        v-if="displayPicker"
    />
    </div>
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
import { Chrome } from "vue-color";

export default {
  name: "BadasoColorPicker",
  components: {
    "color-picker": Chrome,
  },
  data: () => ({
    colors: {
      hex: "#000000",
    },
    colorValue: "",
    displayPicker: false,
  }),
  beforeMount() {
    this.color = this.value
  },
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Color picker",
    },
    placeholder: {
      type: String,
      default: "Color picker",
    },
    value: {
      type: String,
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
    setColor(color) {
      this.updateColors(color);
      this.colorValue = color;
    },
    updateColors(color) {
      if (color.slice(0, 1) == "#") {
        this.colors = {
          hex: color,
        };
      } else if (color.slice(0, 4) == "rgba") {
        var rgba = color.replace(/^rgba?\(|\s+|\)$/g, "").split(","),
          hex =
            "#" +
            (
              (1 << 24) +
              (parseInt(rgba[0]) << 16) +
              (parseInt(rgba[1]) << 8) +
              parseInt(rgba[2])
            )
              .toString(16)
              .slice(1);
        this.colors = {
          hex: hex,
          a: rgba[3],
        };
      }
    },
    showPicker() {
      document.addEventListener("click", this.documentClick);
      this.displayPicker = true;
    },
    hidePicker() {
      document.removeEventListener("click", this.documentClick);
      this.displayPicker = false;
    },
    togglePicker() {
      this.displayPicker ? this.hidePicker() : this.showPicker();
    },
    updateFromInput() {
      this.updateColors(this.colorValue);
    },
    updateFromPicker(color) {
      this.colors = color;
      if (color.rgba.a == 1) {
        this.colorValue = color.hex;
      } else {
        this.colorValue =
          "rgba(" +
          color.rgba.r +
          ", " +
          color.rgba.g +
          ", " +
          color.rgba.b +
          ", " +
          color.rgba.a +
          ")";
      }
    },
    documentClick(e) {
      var el = this.$refs.colorpicker,
        target = e.target;
      if (el !== target && !el.contains(target)) {
        this.hidePicker();
      }
    },
  },
  watch: {
    colorValue(val) {
      if (val) {
        this.updateColors(val);
        this.$emit("input", val);
      }
    },
  },
  mounted() {
    this.setColor(this.color || "#000000");
  },
};
</script>