<template>
  <li
    :class="{ divider: divider }"
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
      :class="{ disabled: disabled }"
      class="badaso-dropdown-item__item--link"
      v-on="$listeners"
    >
      {{ $attrs.disabled }}
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
      :class="{ disabled: disabled }"
      class="badaso-dropdown-item__item--link"
      v-on="$listeners"
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
import _color from "../utils/color.js";
export default {
  name: "BadasoDropdownItem",
  inheritAttrs: false,
  props: {
    to: {},
    disabled: {
      default: false,
      type: Boolean,
    },
    divider: {
      default: false,
      type: Boolean,
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
      default: false,
      type: Boolean,
    },
  },
  data: () => ({
    hoverx: false,
    vsDropDownItem: true,
    color: null,
  }),
  mounted() {
    this.changeColor();
  },
  updated() {
    this.changeColor();
  },
  methods: {
    isRTL(value) {
      if (this.$vs.rtl) {
        return value;
      } else {
        if (value === "right") {
          return "left";
        }
        if (value === "left") {
          return "right";
        }
      }
    },
    closeParent() {
      if (this.disabled) return;
      searchParent(this);
      function searchParent(_this) {
        const parent = _this.$parent;
        if (!parent.$el.className) return;
        if (parent.$el.className.indexOf("parent-dropdown") == -1) {
          searchParent(parent);
        } else {
          const [dropdownMenu] = parent.$children.filter((item) => {
            return item.hasOwnProperty("dropdownVisible");
          });
          dropdownMenu.dropdownVisible = parent.vsDropdownVisible = false;
        }
      }
    },
    changeColor() {
      const _self = this;
      searchParent(this);
      function searchParent(_this) {
        const parent = _this.$parent;
        if (!parent.$el.className) {
          return;
        }
        if (parent.$el.className.indexOf("parent-dropdown") == -1) {
          searchParent(parent);
        } else {
          _self.color = parent.color;
        }
      }
    },
    giveColor(opacity = 1) {
      return _color.rColor(this.color, opacity);
    },
  },
};
</script>
