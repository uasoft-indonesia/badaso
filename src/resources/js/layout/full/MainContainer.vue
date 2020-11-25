<template>
  <div class="main-wrapper">
    <!---Navigation-->
    <Navbar
      :topbarColor="topbarColor"
      :logo="'/badaso-images/logo/logo-light-icon.png'"
      :title="logotitle"
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
  methods: {},
};
</script>