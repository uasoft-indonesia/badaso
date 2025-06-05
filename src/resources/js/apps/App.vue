<template>
  <div id="app">
    <router-view></router-view>
    <badaso-prompt
      :active.sync="loader"
      buttons-hidden
      :title="title"
      type="confirm"
      class="badaso-loader"
      :color="color"
      :headerColor="headerColor"
    >
      <br />
      <vs-progress indeterminate :color="color">primary</vs-progress>
    </badaso-prompt>
    <badaso-prompt
      :active.sync="loaderSync"
      buttons-hidden
      title="Synchron Data Loading"
      type="confirm"
      class="badaso-loader"
      color="primary"
      headerColor="primary"
    >
      <br />
      <vs-progress indeterminate :color="color">primary</vs-progress>
    </badaso-prompt>
  </div>
</template>

<script>
export default {
  name: "app",
  components: {},
  data: () => ({
    loader: false,
    title: "Loading",
    color: "primary",
    headerColor: null,
    loaderSync: false,
  }),
  methods: {
    openLoader(payload = null) {
      this.title = payload ? payload.title : "Loading";
      this.color = payload ? payload.color : "primary";
      this.headerColor = payload ? payload.headerColor : null;
      this.loader = true;
    },
    closeLoader() {
      this.loader = false;
    },
    syncLoader(loaderSyncStatus) {
      this.loaderSync = loaderSyncStatus;
    },
  },
  computed: {
    getSelectedLocale: {
      get() {
        return this.$store.getters["badaso/getSelectedLocale"];
      },
    },
    verified: {
      get() {
        return this.$store.getters["badaso/isVerified"];
      },
    },
    keyIssue: {
      get() {
        return this.$store.state.badaso.keyIssue;
      },
    },
  },
  mounted() {
    this.$i18n.locale = this.getSelectedLocale.key;
    this.$store.commit("badaso/FETCH_CONFIGURATION");
    this.$store.commit("badaso/FETCH_FILE_CONFIGURATION");
  },
  beforeMount() {},
};
</script>
