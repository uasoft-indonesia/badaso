<template>
  <div id="app">
    <router-view v-if="verified && !keyIssue.invalid"></router-view>
    <badaso-loading-page v-else title="Verifying Badaso" />
    <badaso-license-blocker />
    <vs-prompt :active.sync="loader" buttons-hidden :title="message" type="confirm" class="badaso-loader">
      <br />
      <vs-progress indeterminate color="primary">primary</vs-progress>
    </vs-prompt>
    <badaso-loader ref="loader" />
  </div>
</template>

<script>
export default {
  name: "app",
  components: {},
  data: () => ({
    loader: false,
      message: "Loading",
  }),
  methods: {
    openLoader(payload = null) {
      this.message = payload ? payload.message : "Loading";
      this.loader = true;
    },
    closeLoader() {
      this.loader = false;
    },
  },
  computed: {
    getSelectedLocale: {
      get() {
        return this.$store.getters['badaso/getSelectedLocale']
      }
    },
    verified: {
      get() {
        return this.$store.getters['badaso/isVerified']
      }
    },
    keyIssue: {
      get() {
        return this.$store.state.badaso.keyIssue;
      },
    },
  },
  mounted() {
    this.$i18n.locale = this.getSelectedLocale.key
    this.$store.commit("badaso/FETCH_CONFIGURATION");
  },
  beforeMount() {
    this.$store.commit("badaso/VERIFY_BADASO");
  }
};
</script>
