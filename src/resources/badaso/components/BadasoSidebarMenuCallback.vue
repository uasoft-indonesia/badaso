<template>
  <div :class="handleStyleClass().vsSideBarGroupClass">
    <!-- vs-sidebar--item -->
    <div
      :class="`${handleStyleClass().vsSideBarParentItem} ${
        getItemActive().vsSideBarItemActive
      }`"
    >
      <!-- for dropdown -->
      <a
        v-if="isParentMenu"
        href="#"
        aria-current="page"
        :class="getItemActive().vsALinkItemActive"
        @click="clickMenuParentItem()"
      >
        <span class="hide-in-minisidebar flex-row">
          <vs-icon :icon="icon ? icon : 'remove'" />
          {{ title }}</span
        >
        <vs-icon
          v-if="isParentMenu"
          :icon="handleStyleClass().vsSideBarIcon"
          style="margin-right: 0px"
        />
      </a>
      <!-- for route link -->
      <router-link
        v-else
        :to="url"
        aria-current="page"
        :class="getItemActive().vsALinkItemActive"
      >
        <vs-icon :icon="icon ? icon : 'remove'" />
        <span class="hide-in-minisidebar"> {{ title }}</span>
      </router-link>
    </div>
    <!-- vs-sidebar--group-items -->
    <ul
      v-if="isParentMenu"
      class="vs-sidebar--group-child-items"
      :style="handleStyleClass().vsSideBarGroupChildItemStyle"
    >
      <li
        v-for="children in getChildrenMenuItem()"
        :key="JSON.stringify(children)"
        class="vs-sidebar--item"
      >
        <router-link
          v-if="!children.isParentMenu"
          :to="children.url"
          aria-current="page"
        >
          <vs-icon :icon="children.icon ? children.icon : 'remove'" />
          <span class="hide-in-minisidebar">{{ children.title }}</span>
        </router-link>
        <badaso-sidebar-menu
          v-else
          :default-is-expand="children.isExpand"
          :icon="children.icon ? children.icon : 'remove'"
          :is-parent-menu="children.isParentMenu"
          :title="children.title"
          :url="children.url"
          :children="children.children"
        />
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: "BadasoSidebarMenu",
  props: {
    defaultIsExpand: {
      type: Boolean,
      default: true,
    },
    icon: {
      type: String,
      default: "remove",
    },
    title: {
      type: String,
      default: "",
    },
    url: {
      type: String,
      default: "#",
    },
    children: {
      type: Array,
      // eslint-disable-next-line vue/require-valid-default-prop
      default: [],
    },
  },
  computed: {
    isParentMenu() {
      let isParentMenu = false;
      if (this.children) {
        if (this.children.length > 0) {
          isParentMenu = true;
        }
      }
      return isParentMenu;
    },
  },
  methods: {
    clickMenuParentItem() {
      this.defaultIsExpand = !this.defaultIsExpand;
    },
    getChildrenMenuItem() {
      let children = this.children;
      // loop for add isParentMenu to props children menu item
      children = children.map((menuItem, index) => {
        menuItem.isParentMenu = false;
        if (menuItem.children) {
          if (menuItem.children.length > 0) {
            menuItem.isParentMenu = true;
          }
        }
        return menuItem;
      });
      return children;
    },
    getItemActive() {
      const active = false;
      const vsSideBarItemActive = active ? "vs-sidebar-item-active" : "";
      const vsALinkItemActive = active
        ? "router-link-exact-active router-link-active"
        : "";
      return {
        vsSideBarItemActive,
        vsALinkItemActive,
      };
    },
    handleStyleClass() {
      let vsSideBarGroupClass = "vs-sidebar-group vs-sidebar-group-open";
      let vsSideBarGroupChildItemStyle = "max-height: none;";
      let vsSideBarIcon = "keyboard_arrow_up";
      let vsSideBarParentItem = "vs-sidebar--item";
      if (!this.defaultIsExpand) {
        vsSideBarGroupClass = "vs-sidebar-group";
        vsSideBarGroupChildItemStyle = "max-height: 0px;";
        vsSideBarIcon = "keyboard_arrow_down";
      }
      if (this.isParentMenu) {
        vsSideBarParentItem = "vs-sidebar--item vs-sidebar--item-parent";
      }
      return {
        vsSideBarGroupClass,
        vsSideBarGroupChildItemStyle,
        vsSideBarIcon,
        vsSideBarParentItem,
      };
    },
  },
};
</script>
