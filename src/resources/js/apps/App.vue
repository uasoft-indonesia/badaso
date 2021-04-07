<template>
  <div id="app">
    <router-view v-if="verified && !keyIssue.invalid"></router-view>
    <badaso-loading-page v-else title="Verifying Badaso" />
    <badaso-license-blocker />
  </div>
</template>

<script>
export default {
  name: "app",
  components: {},
  data: () => ({}),
  methods: {},
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
