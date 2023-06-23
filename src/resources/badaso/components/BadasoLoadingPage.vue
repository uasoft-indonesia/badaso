<template>
  <div class="badaso-loading-page__wrapper">
    <vs-row class="badaso-loading-page__container">
      <vs-col vs-lg="12" class="badaso-loading-page__box">
        <vs-card class="badaso-loading-page__card">
          <div slot="header">
            <h3 class="badaso-loading-page__title">{{ title }}</h3>
          </div>
          <div>
            <vs-progress indeterminate color="primary">primary</vs-progress>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "BadasoLoadingPage",
  components: {},
  props: {
    title: {
      type: String,
      default: "Loading...",
    },
  },
  data: () => ({}),
  mounted() {},
  computed: {},
};
</script>
