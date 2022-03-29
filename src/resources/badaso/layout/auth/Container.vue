<template>
  <div>
    <vs-row
      class="main-container__background--auth"
      :style="`background-image: url('${authBackgroundImage}')`"
    >
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
      const config = this.$store.getters["badaso/getConfig"];
      return config.authBackgroundImage;
    },
  },
};
</script>
