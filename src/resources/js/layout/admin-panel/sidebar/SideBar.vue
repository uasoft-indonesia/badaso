<template>
  <div id="parentx">
    <vs-sidebar
      default-index="1"
      :parent="parent"
      :hiddenBackground="doNotClose"
      color="primary"
      class="sidebarx"
      spacer
      v-model="sidebarModel"
      :click-not-close="doNotClose"
      :reduce="reduceSidebar"
    >
      <!-- <div class="header-sidebar text-center" slot="header">
        <vs-avatar
          size="70px"
          src="https://randomuser.me/api/portraits/men/85.jpg"
        />
        <h4>
          Steave Jobs<br />
          <small>varun@gmail.com</small>
        </h4>
      </div> -->
      <vs-sidebar-item icon="dashboard" :to="`/${prefix}/home`">
        <span class="hide-in-minisidebar">Dashboard</span>
      </vs-sidebar-item>
      <vs-sidebar-group title="Main Menu" open v-if="mainMenu.length > 0">
        <template v-for="(menu, index) in mainMenu">
          <vs-sidebar-group v-if="menu.children && menu.children.length > 0" :title="menu.title" open>
              <template v-for="(childMenu, indexChildMenu) in menu.children">
                <vs-sidebar-item
                  :icon="childMenu.iconClass"
                  :to="'/'+prefix+'/main/'+childMenu.url"
                  :key="`menu-${index}-${indexChildMenu}`"
                  :index="`${index}.${indexChildMenu}`"
                  :style="`color: ${childMenu.color}`"
                >
                  <span class="hide-in-minisidebar">{{ childMenu.title }}</span>
                </vs-sidebar-item>
              </template>
          </vs-sidebar-group>
          <vs-sidebar-item
            v-else
            :icon="menu.iconClass"
            :to="'/'+prefix+'/main/'+menu.link"
            :key="`menu-${index}`"
            :index="index"
            :style="`color: ${menu.color}`"
          >
            <span class="hide-in-minisidebar">{{ menu.title }}</span>
          </vs-sidebar-item>
        </template>
      </vs-sidebar-group>

      <vs-sidebar-group title="Configuration Menu" open v-if="configurationMenu.length > 0">
        <template v-for="(menu, index) in configurationMenu">
          <vs-sidebar-group v-if="menu.children && menu.children.length > 0" :title="menu.title" open>
              <template v-for="(childMenu, indexChildMenu) in menu.children">
                <vs-sidebar-item
                  :icon="childMenu.iconClass"
                  :to="childMenu.url"
                  :key="`menu-${index}-${indexChildMenu}`"
                  :index="`${index}.${indexChildMenu}`"
                  :style="`color: ${childMenu.color}`"
                >
                  <span class="hide-in-minisidebar">{{ childMenu.title }}</span>
                </vs-sidebar-item>
              </template>
          </vs-sidebar-group>
          <vs-sidebar-item
            v-else
            :icon="menu.iconClass"
            :to="menu.url"
            :key="`menu-${index}`"
            :index="index"
            :style="`color: ${menu.color}`"
          >
            <span class="hide-in-minisidebar">{{ menu.title }}</span>
          </vs-sidebar-item>
        </template>
      </vs-sidebar-group>

    </vs-sidebar>
  </div>
</template>

<script>

export default {
  name: "SideBar",
  props: {
    parent: {
      type: String,
    },
    index: {
      default: null,
      type: [String, Number],
    },
  },
  data: () => ({
    sidebarModel: true,
    doNotClose: false,
    windowWidth: window.innerWidth,
    prefix: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX : 'badaso-admin'
    // mainMenu: [],
  }),
  computed: {
    //This is for mobile trigger
    isSidebarActive: {
      get() {
        return this.$store.state.isSidebarActive;
      },
      set(val) {
        this.$store.commit("IS_SIDEBAR_ACTIVE", val);
      },
    },
    reduceSidebar: {
      get() {
        return this.$store.state.reduceSidebar;
      }
    },
    mainMenu:{
      get() {
        return  this.$store.getters.getMenu
      }
    },
    configurationMenu:{
      get() {
        return  this.$store.getters.getConfigurationMenu
      }
    }
  },
  watch: {},
  methods: {
    handleWindowResize(event) {
      this.windowWidth = event.currentTarget.innerWidth;
      this.setSidebar();
    },
    setSidebar() {
      if (this.windowWidth < 768) {
        this.$store.commit("IS_SIDEBAR_ACTIVE", false);
        this.doNotClose = false;
      } else {
        this.$store.commit("IS_SIDEBAR_ACTIVE", true);
        this.doNotClose = true;
      }
    },
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener("resize", this.handleWindowResize);
    });
    this.setSidebar();
    this.$store.commit("FETCH_MENU");
    this.$store.commit("FETCH_CONFIGURATION_MENU");
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleWindowResize);
    this.setSidebar();
  },
};
</script>
