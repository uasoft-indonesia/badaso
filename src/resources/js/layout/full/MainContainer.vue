<template>
  <div :class="`main-wrapper ${reduceSidebar ? 'main-wrapper-mini' : ''}`">
    <!---Navigation-->
    <Navbar
      :topbarColor="dashboardHeaderColor"
      :logo="dashboardLogo"
      :title="dashboardTitle"
    />
    <!---Sidebar-->
    <SideBar parent=".main-wrapper" :sidebarLinks="sidebarLinks" />
    <!---Page Container-->
    <div class="main-container-fluid">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Navbar from "./header/Navbar.vue";
import SideBar from "./sidebar/SideBar.vue";
import sidebarLinks from "./sidebar/sidebarlinks.js";

export default {
  name: "MainContainer",
  components: {
    Navbar,
    SideBar,
  },
  data: () => ({
    topbarColor: "#2962ff",
    logotitle: "Badaso",
    sidebarLinks: sidebarLinks,
    image: "",
  }),
  computed: {
    dashboardTitle:{
      get() {
        let config = this.$store.getters.getConfig
        return  config.dashboardTitle ?  config.dashboardTitle : 'Badaso'
      }
    },
    dashboardLogo:{
      get() {
        let config = this.$store.getters.getConfig
        return  config.dashboardLogo ?  '/badaso-api/v1/file/view?file='+config.dashboardLogo : '/badaso-images/logo/logo-light-icon.png'
      }
    },
    dashboardHeaderColor: {
      get() {
        let config = this.$store.getters.getConfig
        return  config.dashboardHeaderColor ?  config.dashboardHeaderColor : "#2962ff"
      }
    },
    reduceSidebar: {
      get() {
        return this.$store.state.reduceSidebar;
      }
    },
  },
  mounted() {
    this.$store.commit("FETCH_COMPONENT");
    this.$store.commit("FETCH_CONFIGURATION");
    this.$store.commit("FETCH_USER");
  },
  methods: {
    logout() {
      this.$api.auth
        .logout()
        .then((response) => {
          this.$router.push({name: "Login"})
        })
        .catch((error) => {
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    }
  },
};
</script>