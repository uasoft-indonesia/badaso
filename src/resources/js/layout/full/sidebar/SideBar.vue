<template lang="html">
  <div id="parentx">
    <vs-sidebar
      default-index="1"
      :parent="parent"
      :hiddenBackground="doNotClose"
      color="primary"
      class="sidebarx"
      spacer
      v-model="isSidebarActive"
      :click-not-close="doNotClose"
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
      <vs-sidebar-item icon="dashboard" to="home">
        <span class="hide-in-minisidebar">Dashboard</span>
      </vs-sidebar-item>
      <vs-sidebar-group title="Main Menu" open>
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
      <vs-sidebar-group title="Configuration" open>
        <template v-for="(sidebarLink, index) in sidebarLinks">
          <vs-sidebar-item
            :icon="sidebarLink.icon"
            :to="sidebarLink.url"
            :key="`sidebarLink-${index}`"
            :index="`sidebarLink-${index}`"
          >
            <span class="hide-in-minisidebar">{{ sidebarLink.name }}</span>
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
    sidebarLinks: {
      type: Array,
      required: true,
    },
    index: {
      default: null,
      type: [String, Number],
    },
  },
  data: () => ({
    doNotClose: false,
    windowWidth: window.innerWidth,
    prefix: process.env.MIX_DASHBOARD_ROUTE_PREFIX ? process.env.MIX_DASHBOARD_ROUTE_PREFIX : 'badaso-admin'
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

    mainMenu:{
      get() {
        return  this.$store.getters.getMenu
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
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleWindowResize);
    this.setSidebar();
  },
};
</script>
