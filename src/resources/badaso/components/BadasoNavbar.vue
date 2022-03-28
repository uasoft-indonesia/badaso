<template>
  <header
    :style="[styleNavbar]"
    :class="[
      `vs-navbar-${type}`,
      `vs-navbar-color-${color}`,
      { collapse: collapse },
    ]"
    class="vs-navbar"
  >
    <div v-if="viewType == $constants.DESKTOP" class="top-navbar__header">
      <div class="top-navbar__logo">
        <slot name="logo"> </slot>
      </div>
      <slot name="navigation"> </slot>
      <div class="top-navbar__menu--left">
        <slot name="left_menu"> </slot>
      </div>
      <div class="top-navbar__menu--right">
        <slot name="right_menu"> </slot>
      </div>
    </div>
    <div v-if="viewType == $constants.MOBILE" class="vs-navbar--header">
      <slot name="navigation"> </slot>
      <div class="top-navbar__logo">
        <slot name="logo"> </slot>
        &nbsp;
        <slot name="title"> </slot>
      </div>
      <slot name="right_menu"></slot>
    </div>
  </header>
</template>

<script>
import _color from "../utils/color";
export default {
  name: "BadasoNavbar",

  props: {
    value: {},
    type: {
      default: null,
      type: String,
    },
    collapse: {
      default: false,
      type: Boolean,
    },
    color: {
      type: String,
      default: "transparent",
    },
    activeTextColor: {
      type: String,
      default: "primary",
    },
    textColor: {
      type: String,
      default: "rgb(40,40,40)",
    },
  },

  data: () => ({
    windowWidth: window.innerWidth,
    activeMenuResponsive: false,
    viewType: "desktop",
  }),

  computed: {
    styleNavbar() {
      return {
        background: _color.getColor(this.color),
      };
    },
  },
  methods: {
    changeIndex(index) {
      this.$emit("input", index);
    },
    handleWindowResize(event) {
      this.windowWidth = event.currentTarget.innerWidth;
      this.setViewType();
    },
    setViewType() {
      if (this.windowWidth < 768) {
        this.viewType = this.$constants.MOBILE;
      } else {
        this.viewType = this.$constants.DESKTOP;
      }
    },
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener("resize", this.handleWindowResize);
    });
    this.setViewType();
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleWindowResize);
  },
};
</script>
