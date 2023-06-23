<template>
  <header>
    <badaso-navbar
      v-model="indexActive"
      :color="topbarColor"
      class="top-navbar"
      active-text-color="rgba(255,255,255,1)"
      :style="{ color: topbarFontColor }"
    >
      <div
        slot="logo"
        class="top-navbar__logo-wrapper"
        v-if="
          logoConfig === 'logo_only' ||
          logoConfig === 'logo_and_text' ||
          logoConfig === 'text_only'
        "
      >
        <img
          :src="logo"
          v-if="logoConfig === 'logo_only' || logoConfig === 'logo_and_text'"
          alt="Dashboard"
        />
        <span
          class="top-navbar__logo-text"
          v-if="logoConfig === 'text_only' || logoConfig === 'logo_and_text'"
        >
          {{ title }}
        </span>
        &nbsp;
        <kbd v-if="!isOnline"> offline </kbd>
      </div>
      <div slot="navigation">
        <div
          class="top-navbar__icon"
          @click.stop="reduceSidebar"
          v-if="view == $constants.DESKTOP"
        >
          <vs-icon icon="menu"></vs-icon>
        </div>
        <div
          class="top-navbar__icon"
          @click.stop="activeSidebar"
          v-if="view == $constants.MOBILE"
        >
          <vs-icon icon="menu"></vs-icon>
        </div>
      </div>
      <div slot="left_menu">
        <vs-dropdown vs-trigger-click class="top-navbar__i18n-container">
          <a href="#" :style="{ color: topbarFontColor }">
            {{ getSelectedLocale.label }}
            <vs-icon icon="expand_more" size="small"></vs-icon>
          </a>
          <vs-dropdown-menu>
            <vs-dropdown-item
              v-for="(item, index) in getLocale"
              :key="index"
              v-on:click="setLocale(item)"
            >
              <span v-if="item.label">{{ item.label }}</span>
              <span v-else>{{ item.key }}</span>
            </vs-dropdown-item>
          </vs-dropdown-menu>
        </vs-dropdown>
      </div>
      <div slot="right_menu">
        <badaso-notification-message
          :topbarFontColor="topbarFontColor"
        ></badaso-notification-message>
      </div>
    </badaso-navbar>
  </header>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Navbar",
  components: {},
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
    totalMessageNotification: 0,
    active: false,
  }),
  computed: {
    user: {
      get() {
        const user = this.$store.getters["badaso/getUser"];
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
        return this.$store.getters["badaso/getLocale"];
      },
    },
    getSelectedLocale: {
      get() {
        return this.$store.getters["badaso/getSelectedLocale"];
      },
    },
    isOnline: {
      get() {
        const isOnline = this.$store.getters["badaso/getGlobalState"].isOnline;
        return isOnline;
      },
    },
  },
  methods: {
    // This is for sidebar trigger in mobile
    reduceSidebar() {
      this.$store.commit("badaso/REDUCE_SIDEBAR", !this.isReduceSidebar);
    },
    activeSidebar() {
      this.$store.commit("badaso/IS_SIDEBAR_ACTIVE", !this.isSidebarActive);
    },
    logout() {
      this.$api.badasoAuth
        .logout()
        .then((response) => {
          localStorage.clear();
          this.$router.push({ name: "AuthLogin" });
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
};
</script>
