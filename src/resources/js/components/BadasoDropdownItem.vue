<template>
  <li
    :class="{ divider }"
    :style="{
      color: hoverx ? giveColor() + ' !important' : null,
      background: hoverx ? giveColor(0.01) + ' !important' : null,
    }"
    class="badaso-dropdown-item__container"
    @click="closeParent"
    @mouseover="hoverx = true"
    @mouseout="hoverx = false"
  >
    <router-link
      v-if="to"
      :to="to"
      v-bind="$attrs"
      :class="{ disabled }"
      class="badaso-dropdown-item__item--link"
    >
      <div class="badaso-dropdown-item__item">
        <div>
          <vs-icon
            v-if="icon"
            :icon-pack="iconPack"
            :icon="icon"
            class="badaso-dropdown-item__item--icon"
          ></vs-icon>
        </div>
        <div class="badaso-dropdown-item__item--text">
          <slot />
        </div>
      </div>
    </router-link>

    <a
      v-else
      v-bind="$attrs"
      :class="{ disabled }"
      class="badaso-dropdown-item__item--link"
    >
      <div class="badaso-dropdown-item__item">
        <div>
          <vs-icon
            v-if="icon"
            :icon-pack="iconPack"
            :icon="icon"
            class="badaso-dropdown-item__item--icon"
          ></vs-icon>
        </div>
        <div class="badaso-dropdown-item__item--text">
          <slot />
        </div>
      </div>
    </a>
  </li>
</template>

<script>
import { ref, inject, onMounted } from 'vue';
import _color from "../utils/color.js";

export default {
  name: "BadasoDropdownItem",
  props: {
    to: {
      type: [String, Object],
      default: null,
    },
    disabled: {
      type: Boolean,
      default: false,
    },
    divider: {
      type: Boolean,
      default: false,
    },
    icon: {
      type: String,
      default: null,
    },
    iconPack: {
      type: String,
      default: "material-icons",
    },
    iconAfter: {
      type: Boolean,
      default: false,
    },
  },
  setup(props) {
    const hoverx = ref(false);
    const color = ref(null);
    const parentColor = inject('color', null);

    const closeParent = () => {
      if (props.disabled) return;
      // Logic to close parent dropdown
    };

    const changeColor = () => {
      color.value = parentColor || props.color;
    };

    const giveColor = (opacity = 1) => {
      return _color.rColor(color.value, opacity);
    };

    onMounted(() => {
      changeColor();
    });

    return {
      hoverx,
      color,
      closeParent,
      giveColor,
    };
  },
};
</script>

<style scoped>
/* Add component-specific styles here */
</style>
