<template>
  <div
    :class="[`badaso-collapse__container--${type}`]"
    class="badaso-collapse__container"
  >
    <slot></slot>
  </div>
</template>
<script>
export default {
  name: "BadasoCollapse",
  props: {
    accordion: {
      default: false,
      type: Boolean,
    },
    type: {
      default: "default",
      type: String,
    },
    openHover: {
      default: false,
      type: Boolean,
    },
  },
  methods: {
    emitChange() {
      this.$emit("change");
    },
    closeAllItems(el) {
      const children = this.$children;
      // eslint-disable-next-line array-callback-return
      children.map((item) => {
        if (item.$el !== el) {
          item.maxHeight = "0px";
        }
      });
    },
  },
};
</script>
