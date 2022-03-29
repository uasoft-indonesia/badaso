<template>
  <div
    :class="{
      'badaso-collapse-item--open': maxHeight != '0px',
      'badaso-collapse-item--disabled': disabled,
    }"
    class="badaso-collapse-item__container"
    @mouseover="mouseover"
    @mouseout="mouseout"
  >
    <header class="badaso-collapse-item__header" @click="toggleContent">
      <slot name="header"></slot>
      <span v-if="!notArrow" class="badaso-collapse-item__header-icon">
        <vs-icon :icon-pack="iconPack" :icon="iconArrow" />
      </span>
    </header>
    <div
      ref="content"
      :style="styleContent"
      class="badaso-collapse-item__content-container"
    >
      <div class="badaso-collapse-item__content">
        <slot />
      </div>
    </div>
  </div>
</template>
<script>
export default {
  name: "BadasoCollapseItem",
  components: {},
  props: {
    open: {
      default: false,
      type: Boolean,
    },
    disabled: {
      default: false,
      type: Boolean,
    },
    notArrow: {
      default: false,
      type: Boolean,
    },
    iconArrow: {
      default: "keyboard_arrow_down",
      type: String,
    },
    iconPack: {
      default: "material-icons",
      type: String,
    },
    sst: {
      default: false,
      type: Boolean,
    },
  },
  data: () => ({
    maxHeight: "0px",
    // only used for sst
    dataReady: false,
    padding: "0",
  }),
  computed: {
    accordion() {
      return this.$parent.accordion;
    },
    openHover() {
      return this.$parent.openHover;
    },
    styleContent() {
      return {
        maxHeight: this.maxHeight,
        paddingTop: this.paddingTop,
      };
    },
  },
  watch: {
    maxHeight(newVal) {
      this.$parent.emitChange();
      if (newVal === "unset") {
        this.paddingTop = "15px";
      } else {
        this.paddingTop = "0";
      }
    },
    ready(newVal, oldVal) {
      if (oldVal != newVal && newVal) {
        this.initMaxHeight();
      }
    },
  },
  mounted() {
    window.addEventListener("resize", this.changeHeight);
    if (this.open) {
      this.maxHeight = `unset`;
    }
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.changeHeight);
  },
  methods: {
    changeHeight() {
      if (this.maxHeight != "0px") {
        this.maxHeight = `unset`;
      }
    },
    toggleContent() {
      if (this.openHover || this.disabled) return;
      if (this.accordion) {
        this.$parent.closeAllItems(this.$el);
      }
      if (this.sst && !this.dataReady) {
        this.$emit("fetch", {
          done: () => {
            this.initMaxHeight();
            this.dataReady = true;
          },
        });
      } else {
        this.initMaxHeight();
      }
    },
    initMaxHeight() {
      if (this.maxHeight == "0px") {
        this.maxHeight = `unset`;
      } else {
        this.maxHeight = `0px`;
      }
    },
    mouseover() {
      if (this.disabled) return;
      if (this.openHover) {
        this.maxHeight = `unset`;
      }
    },
    mouseout() {
      if (this.openHover) {
        this.maxHeight = `0px`;
      }
    },
  },
};
</script>
