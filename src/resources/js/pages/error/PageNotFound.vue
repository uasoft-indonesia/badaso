<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >
    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">{{ $t("404.title") }}</h3>
        <p class="mb-0">{{ $t("404.subtitle") }}</p>
      </div>
      <div>
        <vs-button type="relief" class="btn-block" @click="goHome()">{{
          $t("404.button")
        }}</vs-button>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  name: "PageNotFound",
  data: () => ({}),
  methods: {
    goHome() {
      this.$router.push({ name: "AuthLogin" });
    },
  },
};
</script>

<style>
.login-register-box {
  max-width: 400px;
}
</style>
