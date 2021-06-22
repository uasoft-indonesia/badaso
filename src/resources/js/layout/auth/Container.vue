<template>
  <div class="blank_layout">
    <vs-row class="full-height login-register-bg" :style="`background-image: url('${authBackgroundImage}')`">
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
      let url = this.$store.getters["badaso/getMeta"];
      return config.authBackgroundImage ? url.mediaBaseUrl + config.authBackgroundImage : url.mediaBaseUrl + "files/shares/auth-bg.jpg";
    }
  }
};
</script>
