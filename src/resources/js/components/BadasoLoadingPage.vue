<template>
  <div class="blank_layout">
    <vs-row class="full-height login-register-bg">
      <vs-col vs-lg="12" class="login-register-box">
        <vs-card class="mb-0">
          <div slot="header">
            <h3 class="mb-1">{{ title }}</h3>
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
