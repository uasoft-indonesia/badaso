<template>
  <div :class="`main-wrapper ${reduceSidebar ? 'main-wrapper-mini' : ''}`">
    <!---Navigation-->
    <Navbar
      :view="viewType"
      :topbarColor="adminPanelHeaderColor"
      :topbarFontColor="adminPanelHeaderFontColor"
      :logo="adminPanelLogo"
      :title="adminPanelTitle"
      :windowWidth="windowWidth"
      :logoConfig="adminPanelLogoConfig"
    />
    <!---Sidebar-->
    <SideBar parent=".main-wrapper" :doNotClose="this.doNotClose" :view="viewType" />
    <!---Page Container-->
    <div class="main-container-fluid">
      <router-view class="content" :key="$route.path"></router-view>
      <Footer></Footer>
    </div>
    <badaso-license-blocker />
    <badaso-unauthorize />
  </div>
</template>

<script>
import Navbar from "./header/Navbar.vue";
import SideBar from "./sidebar/SideBar.vue";
import Footer from "./footer/Footer.vue";

export default {
  name: "AdminContainer",
  components: {
    Navbar,
    SideBar,
    Footer,
  },
  data: () => ({
    topbarColor: "#2962ff",
    logotitle: "Badaso",
    image: "",
    windowWidth: window.innerWidth,
    viewType: '',
    doNotClose: false,
  }),
  computed: {
    adminPanelTitle:{
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelTitle ?  config.adminPanelTitle : 'Badaso'
      }
    },
    adminPanelLogo:{
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  this.$api.file.view(config.adminPanelLogo)
      }
    },
    adminPanelHeaderColor: {
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelHeaderColor ?  config.adminPanelHeaderColor : "#fff"
      }
    },
    adminPanelLogoConfig: {
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelLogoConfig ?  config.adminPanelLogoConfig : "logo_and_text"
      }
    },
    adminPanelHeaderFontColor: {
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelHeaderFontColor ?  config.adminPanelHeaderFontColor : "#000"
      }
    },
    reduceSidebar: {
      get() {
        return this.$store.state.badaso.reduceSidebar;
      }
    },
    keyIssue: {
      get() {
        return this.$store.state.badaso.keyIssue;
      }
    }
  },
  mounted() {
    this.$store.commit("badaso/FETCH_COMPONENT");
    this.$store.commit("badaso/FETCH_CONFIGURATION");
    this.$store.commit("badaso/FETCH_USER");
    this.$store.commit("badaso/SET_KEY_ISSUE", false);

    this.$nextTick(() => {
      window.addEventListener("resize", this.handleWindowResize);
    });

    this.setSidebar()
    this.setViewType()
  },
  beforeDestroy() {
    window.removeEventListener("resize", this.handleWindowResize);
  },
  methods: {
    logout() {
      this.$api.auth
        .logout()
        .then((response) => {
          this.$router.push({name: "AuthLogin"})
        })
        .catch((error) => {
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    handleWindowResize(event) {
      this.$store.commit("badaso/REDUCE_SIDEBAR", false);
      this.windowWidth = event.currentTarget.innerWidth;
      this.setSidebar()
      this.setViewType()
    },
    setSidebar() {
      if (this.windowWidth < 768) {
        this.$store.commit("badaso/IS_SIDEBAR_ACTIVE", false);
        this.doNotClose = false;
      } else {
        this.$store.commit("badaso/IS_SIDEBAR_ACTIVE", true);
        this.doNotClose = true;
      }
    },
    setViewType() {
      if (this.windowWidth < 768) {
        this.viewType = this.$constants.MOBILE
      } else {
        this.viewType = this.$constants.DEKSTOP
      }
    }
  },
};
</script>
<style>
  .content {
    min-height: calc(100vh - 142px);
  }
</style>