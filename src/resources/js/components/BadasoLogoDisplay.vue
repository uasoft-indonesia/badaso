<template>
  <img v-if="adminPanelLogo && adminPanelLogo != ''" :src="adminPanelLogo" alt="adminPanelTitle" class="badaso-logo-display" />
</template>

<script>
export default {
  name: "BadasoLogoDisplay",
  components: {},
  data: () => ({}),
  computed: {
    adminPanelTitle: {
      get() {
        let config = this.$store.getters["badaso/getConfig"];
        return config.adminPanelTitle ? config.adminPanelTitle : "Badaso";
      },
    },
    adminPanelLogo: {
      get() {
        let config = this.$store.getters["badaso/getConfig"];
        return config.adminPanelLogo;
      },
    },
    adminPanelHeaderColor: {
      get() {
        let config = this.$store.getters["badaso/getConfig"];
        return config.adminPanelHeaderColor
          ? config.adminPanelHeaderColor
          : "#fff";
      },
    },
    adminPanelLogoConfig: {
      get() {
        let config = this.$store.getters["badaso/getConfig"];
        return config.adminPanelLogoConfig
          ? config.adminPanelLogoConfig
          : "logo_and_text";
      },
    },
    adminPanelHeaderFontColor: {
      get() {
        let config = this.$store.getters["badaso/getConfig"];
        return config.adminPanelHeaderFontColor
          ? config.adminPanelHeaderFontColor
          : "#000";
      },
    },
  },
};
</script>
