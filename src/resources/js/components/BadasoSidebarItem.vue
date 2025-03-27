<template>
  <div
    :class="{ 'vs-sidebar-item-active': isActive }"
    class="vs-sidebar--item "
    @click="handleClick"
  >
    <router-link v-if="to && target === '_self'" :to="to">
      <vs-icon :icon-pack="iconPack" :icon="icon"> </vs-icon>
      <slot></slot>
    </router-link>
    <a v-else-if="to && target != '_self'" :target="target" :href="to">
      <vs-icon :icon-pack="iconPack" :icon="icon"> </vs-icon>
      <slot></slot>
    </a>
    <a v-else :target="target" :href="href">
      <vs-icon :icon-pack="iconPack" :icon="icon"> </vs-icon>
      <slot></slot>
    </a>
  </div>
</template>
<script>
import { inject } from 'vue';
export default {
  name: "BadasoSidebarItem",
  props: {
    icon: {
      default: null,
      type: String,
    },
    iconPack: {
      default: "material-icons",
      type: String,
    },
    href: {
      default: "#",
      type: String,
    },
    target: {
      default: "_self",
      type: String,
    },
    to: {
      default: null,
      type: [String, Object],
    },
    index: {
      default: null,
      type: [String, Number],
    },
  },
   setup(props) {
    const sidebar = inject('sidebar');
    const getActive = sidebar.getActive;
    const setIndexActive = sidebar.setIndexActive;

    return {
      getActive,
      setIndexActive,
      isActive: getActive() === props.index,
      handleClick: () => setIndexActive(props.index),
    };
  },
};
</script>
