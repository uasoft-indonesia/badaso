<template>
  <div class="blank_layout">
    <vs-row class="full-height login-register-bg"  :style="`background-image: url('${authBackgroundImage}')`">
      <router-view :key="$route.path"></router-view>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "AuthContainer",
  components: {},
  computed: {
    authBackgroundImage() {
      let config = this.$store.getters["badaso/getConfig"];
      let url = this.$store.state.badaso.meta.mediaBaseUrl
      return config.authBackgroundImage ? url + config.authBackgroundImage : url + "files/shares/auth-bg.jpg";
    }
  }
};
</script>
