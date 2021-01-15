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
  mounted() {
    this.$store.commit("FETCH_CONFIGURATION_GROUPS");
  }
};
</script>
