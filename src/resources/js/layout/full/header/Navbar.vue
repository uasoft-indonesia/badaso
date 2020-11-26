<template>
  <header class="gridx">
    <vs-navbar
      v-model="indexActive"
      :color="topbarColor"
      class="topnavbar"
      text-color="rgba(255,255,255,0.7)"
      active-text-color="rgba(255,255,255,1)"
    >
      <!---
      Template logo
      -->
      <div slot="title" class="themelogo">
        <img :src="logo" v-if="logo" alt="Dashboard" />
        <span class="logo-text" v-if="title">{{ title }}</span>
      </div>
      <!---
      Mobile toggle
      -->
      <div slot="title">
        <div class="hiddenDesktop cursor-pointer" @click.stop="activeSidebar">
          <vs-icon icon="menu"></vs-icon>
        </div>
      </div>

      <vs-spacer></vs-spacer>

      <!---
      Craete new dd
      -->
      <vs-dropdown
        vs-trigger-click
        left
        class="cursor-pointer pr-2 pl-2 ml-1 mr-1"
      >
        <a class="text-white-dark" href="#"
          ><vs-icon icon="notifications"></vs-icon
        ></a>
        <vs-dropdown-menu class="topbar-dd">
          <vs-dropdown-item>Action</vs-dropdown-item>
          <vs-dropdown-item>Another Action</vs-dropdown-item>
          <vs-dropdown-item>Something</vs-dropdown-item>
          <vs-dropdown-item>Here</vs-dropdown-item>
        </vs-dropdown-menu>
      </vs-dropdown>
      <!---
      Craete new dd
      -->
      <vs-dropdown
        vs-trigger-click
        left
        class="cursor-pointer pr-2 pl-2 ml-1 mr-1"
      >
        <a class="text-white-dark" href="#"
          ><vs-icon icon="mode_comment"></vs-icon
        ></a>
        <vs-dropdown-menu class="topbar-dd">
          <vs-dropdown-item>Action</vs-dropdown-item>
          <vs-dropdown-item>Another Action</vs-dropdown-item>
          <vs-dropdown-item>Something</vs-dropdown-item>
          <vs-dropdown-item>Here</vs-dropdown-item>
        </vs-dropdown-menu>
      </vs-dropdown>
      <!---
      Craete new dd
      -->
      <vs-dropdown
        vs-trigger-click
        left
        class="cursor-pointer pr-2 pl-2 ml-1 mr-md-3"
      >
        <a class="text-white-dark user-image" href="#"
          ><img src="/badaso-images/users/3.jpg" alt="User"
        /></a>
        <vs-dropdown-menu class="topbar-dd">
          <vs-dropdown-item
            ><vs-icon icon="person_outline" class="mr-1"></vs-icon> My
            Profile</vs-dropdown-item
          >
          <vs-dropdown-item
            ><vs-icon icon="sentiment_very_satisfied" class="mr-1"></vs-icon> My
            Balance</vs-dropdown-item
          >
          <vs-dropdown-item
            ><vs-icon icon="mail_outline" class="mr-1"></vs-icon>
            Inbox</vs-dropdown-item
          >
          <hr class="mb-1" />
          <vs-dropdown-item
            @click="logout()"
            ><vs-icon icon="gps_not_fixed" class="mr-1"></vs-icon>
            Logout</vs-dropdown-item
          >
        </vs-dropdown-menu>
      </vs-dropdown>
    </vs-navbar>
  </header>
</template>

<script>
export default {
  name: "Navbar",
  props: {
    topbarColor: {
      type: String,
      default: "#2962ff",
    },
    title: {
      type: String,
    },
    logo: {
      type: String,
    },
  },
  data: () => ({
    indexActive: 0,
    showToggle: false,
  }),

  methods: {
    //This is for sidebar trigger in mobile
    activeSidebar() {
      this.$store.commit("IS_SIDEBAR_ACTIVE", true);
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
    },
  },
};
</script>
