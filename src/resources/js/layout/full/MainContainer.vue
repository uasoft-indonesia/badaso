<template>
  <div class="main-wrapper">
    <!---Navigation-->
    <Navbar
      :topbarColor="topbarColor"
      :logo="'/badaso-images/logo/logo-light-icon.png'"
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
        return  config.dashboard_title ?  config.dashboard_title : 'Badaso'
      }
    }
  },
  mounted() {
    this.$store.commit("FETCH_COMPONENT");
    this.$store.commit("FETCH_CONFIGURATION");
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