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
      <div slot="header">
        <h3 class="mb-1">Email Verification</h3>
      </div>
      <div v-if="processing">
        Email Verification in progress ....
      </div>
      <div v-else class="text-danger">
        Email Verification failed
      </div>
      <vs-button v-if="expired" type="relief" class="btn-block" @click="requestVerificationToken()">Request Again</vs-button>
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
    processing: true,
    expired: false,
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
        this.processing = false;
        this.errors = error.errors;
        this.$vs.loading.close()
        if (error.message && error.message === 'EXPIRED') {
          this.expired = true;
          this.$vs.notify({title: this.$t('alert.danger'),text:'Verification token expired',color:'danger'})
        } else {
          this.$vs.notify({title: this.$t('alert.danger'),text:error.message,color:'danger'})
        }
      })
    },
    requestVerificationToken() {
      this.$vs.loading({
        type:'sound',
      })
      this.$api.auth.reRequestVerificationToken({
        token: this.token,
        email: this.email,
      })
      .then((response) => {
        this.$vs.loading.close()
        this.$vs.notify({title: this.$t('alert.success'),text:response.data.message,color:'success'})
        this.$router.push({ name: 'Login'})
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
