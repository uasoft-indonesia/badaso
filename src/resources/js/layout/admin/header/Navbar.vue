<template>
  <header class="gridx">
    <badaso-navbar
      v-model="indexActive"
      :color="topbarColor"
      class="topnavbar"
      active-text-color="rgba(255,255,255,1)"
      :style="{ color: topbarFontColor }"
    >
      <div slot="logo" v-if="logoConfig === 'logo_only' || logoConfig === 'logo_and_text'">
        <img :src="logo" v-if="logo" alt="Dashboard" />
      </div>
      <div slot="title" v-if="logoConfig === 'text_only' || logoConfig === 'logo_and_text'">
        <span class="logo-text" v-if="title">{{ title }}</span>
      </div>
      <div slot="navigation">
        <div class="cursor-pointer" @click.stop="reduceSidebar" v-if="view == $constants.DEKSTOP">
          <vs-icon icon="menu"></vs-icon>
        </div>
        <div class="cursor-pointer" @click.stop="activeSidebar" v-if="view == $constants.MOBILE">
          <vs-icon icon="menu"></vs-icon>
        </div>
      </div>
      <div slot="left_menu">
        <vs-dropdown vs-trigger-click class="ml-4">
          <a class="a-icon" href="#" :style="{ color: topbarFontColor }">
            {{ getSelectedLocale.label }}
            <vs-icon icon="expand_more" size="small"></vs-icon>
          </a>

          <vs-dropdown-menu>
            <vs-dropdown-item
              v-for="(item, index) in getLocale"
              :key="index"
              v-on:click="setLocale(item)"
            >
              {{ item.label }}
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
      <div slot="right_menu">
        <vs-dropdown
          vs-trigger-click
          left
          class="cursor-pointer ml-1 mr-md-3"
        >
          <a class="text-white-dark" href="#"
            ><vs-icon icon="notifications"></vs-icon
          ></a>
          <vs-dropdown-menu class="topbar-dd">
            <div
              class="d-flex align-items-center p-3 bg-danger text-white mb-2 preview"
            >
              <div class="ml-2">
                <h4 class="mb-0 text-white">0 New</h4>
                <p class="mb-0">Notification</p>
              </div>
            </div>
            <div style="max-height: 200px; overflow-y: auto;">
              <vs-dropdown-item>
                <vs-icon icon="person_outline" class="mr-1"></vs-icon>
                Coming soon...
              </vs-dropdown-item>
            </div>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
    </badaso-navbar>
  </header>
</template>

<script>
export default {
  name: "Navbar",
  components: {
  },
  props: {
    topbarColor: {
      type: String,
      default: "#000000",
    },
    topbarFontColor: {
      type: String,
      default: "#06bbd3",
    },
    title: {
      type: String,
    },
    logo: {
      type: String,
    },
    logoConfig: {
      type: String,
    },
    view: {
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
        let user = this.$store.getters['badaso/getUser'];
        return user;
      },
    },
    isSidebarActive: {
      get() {
        return this.$store.state.badaso.isSidebarActive;
      },
    },
    isReduceSidebar: {
      get() {
        return this.$store.state.badaso.reduceSidebar;
      },
    },
    getLocale: {
      get() {
        return this.$store.getters['badaso/getLocale'];
      },
    },
    getSelectedLocale: {
      get() {
        return this.$store.getters['badaso/getSelectedLocale'];
      },
    },
  },
  methods: {
    //This is for sidebar trigger in mobile
    reduceSidebar() {
      this.$store.commit("badaso/REDUCE_SIDEBAR", !this.isReduceSidebar);
    },
    activeSidebar() {
      this.$store.commit("badaso/IS_SIDEBAR_ACTIVE", !this.isSidebarActive);
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
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    setLocale(item) {
      this.$i18n.locale = item.key;
      this.$store.commit("badaso/SET_LOCALE", item);
    },
  },
  mounted() {},
};
</script>
