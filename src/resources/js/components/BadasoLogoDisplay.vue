<template>
  <img
    v-if="adminPanelLogo && adminPanelLogo != ''"
    :src="adminPanelLogo"
    alt="adminPanelTitle"
    style="max-width: 100%; max-height: 30px; float: right;"
  />
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
        if (config.adminPanelLogo && config.adminPanelLogo != "") {
          return this.$api.badasoFile.view(config.adminPanelLogo);
        } else {
          return null;
        }
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
