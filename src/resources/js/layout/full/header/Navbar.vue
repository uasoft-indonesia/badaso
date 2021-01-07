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
        <div class="cursor-pointer" @click.stop="reduceSidebar">
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
          ><img :src="`/badaso-api/v1/file/view?file=${user.avatar}`" alt="User"
        /></a>
        <vs-dropdown-menu class="topbar-dd">
          <div class="d-flex align-items-center p-3 bg-danger text-white mb-2">
            <div>
              <img
                :src="`/badaso-api/v1/file/view?file=${user.avatar}`"
                alt="user"
                width="60"
                class="rounded-circle"
              />
            </div>
            <div class="ml-2">
              <h4 class="mb-0 text-white">{{ user.name }}</h4>
              <p class="mb-0">{{ user.email }}</p>
            </div>
          </div>
          <vs-dropdown-item
            :to="{
              name: 'UserRead',
              params: { id: user.id },
            }"
            ><vs-icon icon="person_outline" class="mr-1"></vs-icon> My
            Profile</vs-dropdown-item
          >
          <hr class="mb-1" />
          <button
            @click="logout()"
            type="button"
            name="button"
            class="vs-component vs-button rounded-button ml-3 mb-3 vs-button-danger vs-button-filled small"
          >
            <span
              class="vs-button-backgroundx vs-button--background"
              style="opacity: 1; left: 20px; top: 20px; width: 0px; height: 0px; transition: width 0.3s ease 0s, height 0.3s ease 0s, opacity 0.3s ease 0s;"
            ></span
            ><!----><span class="vs-button-text vs-button--text">Logout</span
            ><span
              class="vs-button-linex"
              style="top: auto; bottom: -2px; left: 50%; transform: translate(-50%);"
            ></span>
          </button>
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
  computed: {
    user: {
      get() {
        let user = this.$store.getters.getUser;
        return user;
      },
    },
  },
  methods: {
    //This is for sidebar trigger in mobile
    reduceSidebar() {
      this.$store.commit("REDUCE_SIDEBAR");
    },
    logout() {
      this.$api.auth
        .logout()
        .then((response) => {
          localStorage.clear();
          this.$router.push({ name: "Login" });
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
