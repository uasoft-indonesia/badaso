<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >

    <vs-alert color="success" active="true" v-if="$router.currentRoute.params.msg" class="mb-2">
      {{ $router.currentRoute.params.msg }}
    </vs-alert>

    <vs-card class="mb-0">
      <div>
        Loading..
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  data: () => ({
    email: "",
    token: "",
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
    errors: {},
  }),
  mounted() {
    this.email = this.$route.query.email
    this.token = this.$route.query.token
    this.verify()
  },
  methods: {
    verify() {
      this.$vs.loading({
        type:'sound',
      })
      this.$api.auth.verify({
        email: this.email,
        token: this.token
      })
      .then((response) => {
        this.$vs.loading.close()
        this.$router.push({ name: 'Home'})
      })
      .catch((error) => {
        this.errors = error.errors;
        this.$vs.loading.close()
        this.$vs.notify({title: this.$t('alert.danger'),text:error.message,color:'danger'})
      })
    },
  }
};
</script>

<style>
.login-register-box {
  max-width: 400px;
}
</style>
