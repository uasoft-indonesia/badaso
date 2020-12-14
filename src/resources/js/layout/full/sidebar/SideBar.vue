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
      <div class="header-sidebar text-center" slot="header">
        <vs-avatar
          size="70px"
          src="https://randomuser.me/api/portraits/men/85.jpg"
        />
        <h4>
          Steave Jobs<br />
          <small>varun@gmail.com</small>
        </h4>
      </div>
      <vs-sidebar-item icon="dashboard" to="home">
        <span class="hide-in-minisidebar">Dashboard</span>
      </vs-sidebar-item>
      <vs-sidebar-group title="Main Menu" open>
        <template v-for="(menu, index) in mainMenu">
          <vs-sidebar-item
            :icon="menu.icon"
            :to="menu.link"
            :key="`menu-${index}`"
            :index="index"
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
            :index="index"
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
    getMainMenu() {
       this.$vs.loading({
        type:'sound',
      })
      const menuKey = process.env.MIX_DEFAULT_MENU ? process.env.MIX_DEFAULT_MENU : 'admin';
      const prefix = process.env.MIX_DASHBOARD_ROUTE_PREFIX ? process.env.MIX_DASHBOARD_ROUTE_PREFIX : 'badaso-admin';
      this.$api.menu.browseItemByKey({
        menu_key: menuKey
      })
        .then((res) => {
          this.$vs.loading.close()
          let menuItems = res.records
          for (var i = 0, len = menuItems.length; i < len; i++) {
            menuItems[i].link = '/'+prefix+'/main/'+menuItems[i].url
          }
          this.mainMenu = menuItems
        })
        .catch((err) => {
          this.$vs.loading.close()
          this.$vs.notify({title:'Danger',text:err.message,color:'danger'})
        });
    },
  },
  mounted() {
    this.$nextTick(() => {
      window.addEventListener("resize", this.handleWindowResize);
    });
    this.setSidebar();
    // this.getMainMenu();
    this.$store.commit("FETCH_MENU");
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleWindowResize);
    this.setSidebar();
  },
};
</script>
