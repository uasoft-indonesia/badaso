<template>
  <vs-col vs-lg="12" class="main-container__box--auth">
    <vs-card class="main-container__card--auth">
      <div slot="header">
        <h3 class="page-not-found__title">{{ $t("404.title") }}</h3>
        <p class="page-not-found__subtitle">{{ $t("404.subtitle") }}</p>
      </div>
      <div>
        <vs-button
          type="relief"
          class="page-not-found__button"
          @click="goHome()"
          >{{ $t("404.button") }}</vs-button
        >
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
