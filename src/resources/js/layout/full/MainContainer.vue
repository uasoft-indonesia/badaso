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
  mounted() {
    this.setLastAccess()
  },
  methods: {
    setLastAccess() {
      let lastAccessCode = localStorage.getItem('accessCode')
      let date = new Date();
      let timeNow = date.getTime();
      if (lastAccessCode == null) {
        localStorage.setItem('accessCode', window.btoa(timeNow))
      } else {
        let timeLast = window.atob(lastAccessCode)
        let diffInMilli = timeNow - timeLast
        let diffInSecond = diffInMilli / 1000
        let diffInMinute = diffInSecond / 60
        let diffInHour = diffInMinute / 60
        if (diffInHour > 24) {
          this.logout()
        } else {
          localStorage.setItem('accessCode', window.btoa(timeNow))
        }
      }
    },
    logout() {
      this.$api.auth
        .logout()
        .then((response) => {
          console.log(response)
          localStorage.clear()
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