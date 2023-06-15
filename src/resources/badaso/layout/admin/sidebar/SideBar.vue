<template>
  <div id="parentx">
    <vs-sidebar
      default-index="1"
      :parent="parent"
      :hiddenBackground="doNotClose"
      color="primary"
      class="sidebarx badaso-sidebar"
      spacer
      v-model="isSidebarActive"
      :click-not-close="doNotClose"
      :reduce="reduceSidebar"
    >
      <div class="header-sidebar text-center" slot="header">
        <vs-avatar size="70px" :src="getAvatar" />
        <badaso-sidebar-group
          :title="user.name"
          :subTitle="user.email"
          icon="person_pin"
        >
          <badaso-sidebar-item
            v-if="user.id"
            icon="person_outline"
            :to="{
              name: 'UserProfile',
            }"
          >
            Profile
          </badaso-sidebar-item>
          <badaso-sidebar-item icon="logout" @click="logout()">
            Logout
          </badaso-sidebar-item>
        </badaso-sidebar-group>
        <vs-select
          v-model="selectedLang"
          width="100%"
          style="padding: 10px"
          v-if="view == $constants.MOBILE"
        >
          <vs-select-item
            :key="index"
            :value="item.key ? item.key : item"
            :text="item.label ? item.label : item.key ? item.key : item"
            v-for="(item, index) in getLocale"
          />
        </vs-select>
      </div>

      <template v-for="(displayMenu, indexMenu) in mainMenu">
        <!-- if show header -->
        <badaso-sidebar-group
          :title="displayMenu.menu.displayName"
          :open="displayMenu.menu.isExpand == 1"
          :icon="displayMenu.menu.icon"
          :key="indexMenu"
          v-if="
            displayMenu.menuItems &&
            displayMenu.menuItems.length > 0 &&
            displayMenu.menu.isShowHeader
          "
        >
          <template v-for="(menu, index) in displayMenu.menuItems">
            <badaso-sidebar-menu
              :defaultIsExpand="menu.isExpand == 1"
              :title="menu.title"
              :url="menu.url"
              :icon="menu.icon"
              :children="menu.children"
              :key="index"
            />
          </template>
        </badaso-sidebar-group>

        <!-- else hidden header -->
        <div :key="indexMenu" v-else>
          <template v-for="(menu, index) in displayMenu.menuItems">
            <badaso-sidebar-menu
              :defaultIsExpand="menu.isExpand"
              :title="menu.title"
              :url="menu.url"
              :icon="menu.icon"
              :children="menu.children"
              :key="index"
            />
          </template>
        </div>
      </template>
    </vs-sidebar>
  </div>
</template>

<script>
import _ from "lodash";

export default {
  name: "SideBar",
  components: {},
  props: {
    parent: {
      type: String,
    },
    index: {
      default: null,
      type: [String, Number],
    },
    doNotClose: {
      default: false,
      type: Boolean,
    },
    view: {
      type: String,
      default: "desktop",
    },
  },
  data: () => ({
    sidebarModel: true,
    windowWidth: window.innerWidth,
    prefix: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-dashboard",
  }),
  computed: {
    // This is for mobile trigger
    isSidebarActive: {
      get() {
        return this.$store.state.badaso.isSidebarActive;
      },
      set(val) {
        this.$store.commit("badaso/IS_SIDEBAR_ACTIVE", val);
      },
    },
    reduceSidebar: {
      get() {
        return this.$store.state.badaso.reduceSidebar;
      },
    },
    mainMenu: {
      get() {
        return this.$store.getters["badaso/getMenu"];
      },
    },
    configurationMenu: {
      get() {
        return this.$store.getters["badaso/getConfigurationMenu"];
      },
    },
    user: {
      get() {
        const user = this.$store.getters["badaso/getUser"];
        return user;
      },
    },
    getLocale: {
      get() {
        return this.$store.getters["badaso/getLocale"];
      },
    },
    selectedLang: {
      get() {
        let selected = this.$store.getters["badaso/getSelectedLocale"];
        if (selected.key) {
          selected = selected.key;
        }
        return selected;
      },
      set(val) {
        this.setLocale(_.find(this.getLocale, ["key", val]));
      },
    },
    adminPanelHeaderColor() {
      const config = this.$store.getters["badaso/getConfig"];
      return config.adminPanelHeaderColor
        ? config.adminPanelHeaderColor
        : "#ffffff";
    },
    getAvatar() {
      const user = this.$store.getters["badaso/getUser"];
      return user.avatar;
    },
  },
  methods: {
    open(url) {
      if (!this.doNotClose) {
        this.isSidebarActive = false;
      }
      window.open(url);
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
  watch: {
    adminPanelHeaderColor: {
      handler(val, oldVal) {
        document
          .querySelectorAll(".badaso-sidebar .vs-sidebar--items")
          .forEach((element) => {
            try {
              let vsScrollPrimary = "255,255,255";
              if (val.substring(0, 1) == "#") {
                vsScrollPrimary = this.$helper.hexToVsPrimary(val);
              } else {
                vsScrollPrimary = this.$helper.rgbToVsPrimary(val);
              }
              element.style.setProperty(
                "--vs-scrollbar-primary",
                vsScrollPrimary
              );
            } catch (error) {
              console.log(val, error);
            }
          });
      },
    },
  },
  mounted() {
    this.$store.commit("badaso/FETCH_MENU");
    this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
  },
};
</script>
