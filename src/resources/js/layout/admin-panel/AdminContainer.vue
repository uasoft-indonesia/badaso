<template>
  <div :class="`main-wrapper ${reduceSidebar ? 'main-wrapper-mini' : ''}`">
    <!---Navigation-->
    <Navbar
      :topbarColor="adminPanelHeaderColor"
      :topbarFontColor="adminPanelHeaderFontColor"
      :logo="adminPanelLogo"
      :title="adminPanelTitle"
    />
    <!---Sidebar-->
    <SideBar parent=".main-wrapper" />
    <!---Page Container-->
    <div class="main-container-fluid">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
import Navbar from "./header/Navbar.vue";
import SideBar from "./sidebar/SideBar.vue";

export default {
  name: "AdminContainer",
  components: {
    Navbar,
    SideBar,
  },
  data: () => ({
    topbarColor: "#2962ff",
    logotitle: "Badaso",
    image: "",
  }),
  computed: {
    adminPanelTitle:{
      get() {
        let config = this.$store.getters.getConfig
        return  config.adminPanelTitle ?  config.adminPanelTitle : 'Badaso'
      }
    },
    adminPanelLogo:{
      get() {
        let config = this.$store.getters.getConfig
        return  this.$api.file.view(config.adminPanelLogo)
      }
    },
    adminPanelHeaderColor: {
      get() {
        let config = this.$store.getters.getConfig
        return  config.adminPanelHeaderColor ?  config.adminPanelHeaderColor : "#000000"
      }
    },
    adminPanelHeaderFontColor: {
      get() {
        let config = this.$store.getters.getConfig
        return  config.adminPanelHeaderFontColor ?  config.adminPanelHeaderFontColor : "#06bbd3"
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