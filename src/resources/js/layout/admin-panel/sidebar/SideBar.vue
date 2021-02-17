<template>
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
        <span class="hide-in-minisidebar">{{ $t('sidebar.dashboard') }}</span>
      </vs-sidebar-item>
      <vs-sidebar-group :title="$t('sidebar.mainMenu')" open v-if="mainMenu.length > 0">
        <template v-for="(menu, index) in mainMenu">
          <vs-sidebar-group v-if="menu.children && menu.children.length > 0" :title="menu.title" open>
              <template v-for="(childMenu, indexChildMenu) in menu.children">
                <vs-sidebar-item
                  :icon="childMenu.iconClass ? childMenu.iconClass : 'remove'"
                  @click="open(childMenu.url)"
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
            :icon="menu.iconClass ? menu.iconClass : 'remove'"
            @click="open(menu.url)"
            :key="`menu-${index}`"
            :index="index"
            :style="`color: ${menu.color}`"
          >
            <span class="hide-in-minisidebar">{{ menu.title }}</span>
          </vs-sidebar-item>
        </template>
      </vs-sidebar-group>

      <vs-sidebar-group :title="$t('sidebar.configurationMenu')" open v-if="configurationMenu.length > 0">
        <template v-for="(menu, index) in configurationMenu">
          <vs-sidebar-group v-if="menu.children && menu.children.length > 0" :title="menu.title" open>
              <template v-for="(childMenu, indexChildMenu) in menu.children">
                <vs-sidebar-item
                  :icon="childMenu.iconClass ? childMenu.iconClass : 'remove'"
                  @click="open(childMenu.url)"
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
            :icon="menu.iconClass ? menu.iconClass : 'remove'"
            :key="`menu-${index}`"
            :index="index"
            :style="`color: ${menu.color}`"
            @click="open(menu.url)"
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
    doNotClose: {
      default: false,
      type: Boolean
    }
  },
  data: () => ({
    sidebarModel: true,
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
    open(url) {
      if (!this.doNotClose) {
        this.isSidebarActive = false;
      }
      if (this.$helper.isValidHttpUrl(url)) {
        window.open(url)
      } else {
        this.$router.push(url);
      }
    }
  },
  mounted() {
    this.$store.commit("FETCH_MENU");
    this.$store.commit("FETCH_CONFIGURATION_MENU");
  },
};
</script>
