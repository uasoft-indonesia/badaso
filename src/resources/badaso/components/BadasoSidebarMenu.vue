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
        @click="clickMenuParentItem()"
        href="#"
        aria-current="page"
        :class="getItemActive().vsALinkItemActive"
        v-if="isParentMenu"
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
      <template v-else>
        <a
          v-if="url.substring(0, 4) == 'http'"
          :href="url"
          aria-current="page"
          :class="getItemActive().vsAlinkActive"
        >
          <vs-icon :icon="icon ? icon : 'remove'" />
          <span class="hide-in-minisidebar"> {{ title }}</span>
        </a>
        <router-link
          :to="url"
          aria-current="page"
          :class="getItemActive().vsALinkItemActive"
          v-else
        >
          <vs-icon :icon="icon ? icon : 'remove'" />
          <span class="hide-in-minisidebar"> {{ title }}</span>
        </router-link>
      </template>
    </div>
    <!-- vs-sidebar--group-items -->
    <ul
      class="vs-sidebar--group-child-items"
      v-if="isParentMenu"
      :style="handleStyleClass().vsSideBarGroupChildItemStyle"
    >
      <li
        :key="JSON.stringify(children)"
        v-for="children in getChildrenMenuItem()"
        class="vs-sidebar--item"
      >
        <router-link
          :to="children.url"
          v-if="!children.isParentMenu"
          aria-current="page"
        >
          <vs-icon :icon="children.icon ? children.icon : 'remove'" />
          <span class="hide-in-minisidebar">{{
            children.title
          }}</span></router-link
        >
        <badaso-sidebar-menu-callback
          v-else
          :defaultIsExpand="children.isExpand"
          :icon="children.icon ? children.icon : 'remove'"
          :isParentMenu="children.isParentMenu"
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
      default: () => {
        // eslint-disable-next-line no-unused-expressions
        [];
      },
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
          if (menuItem.childrenß.length > 0) {
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
