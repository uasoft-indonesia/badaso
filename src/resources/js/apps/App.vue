<template>
  <div id="app">
    <router-view></router-view>
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
  },
  mounted() {
    this.$store.commit("badaso/FETCH_CONFIGURATION_GROUPS");
    this.$i18n.locale = this.getSelectedLocale.key
  }
};
</script>
