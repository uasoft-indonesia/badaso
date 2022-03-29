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
      children.map((item) => {
        if (item.$el !== el) {
          item.maxHeight = "0px";
        }

        return item;
      });
    },
  },
};
</script>
