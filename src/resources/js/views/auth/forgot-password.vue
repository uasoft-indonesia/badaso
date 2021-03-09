<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >

    <vs-alert :active="res.active" :color="res.status" :icon="res.icon" class="mb-2" >
      <span>{{ res.message }}</span>
    </vs-alert>

    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">{{ $t('forgotPassword.title') }}</h3>
        <p class="mb-0">{{ $t('forgotPassword.subtitle') }}</p>
      </div>
      <div>
        <vs-input
          icon="email"
          icon-after
          size="default"
          :placeholder="$t('forgotPassword.field.email')"
          v-model="email"
          class="w-100 mb-4 mt-2 "
          @keyup.enter="forgotPassword()"
        />
        <vs-button type="relief" class="btn-block" @click="forgotPassword()">{{ $t('forgotPassword.button') }}</vs-button>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  data: () => ({
    email: "",
    password: "",
    res: {
      active: false,
      icon: "",
      status: "",
      message: ""
    },
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
  }),
  methods: {
    forgotPassword() {
      this.$vs.loading({
        type:'sound',
      })
      this.$api.auth.forgotPassword({
        email: this.email,
      })
      .then((response) => {
        this.$vs.loading.close()
        this.res = {
          status: "success",
          icon: "done",
          message: this.$t('forgotPassword.message.success')
        }
      })
      .catch((error) => {
        this.$vs.loading.close()
        this.res = {
          status: "danger",
          icon: "dangerous",
          message: this.$t('forgotPassword.message.error')
        }
      })
    }
  }
};
</script>

<style>
.login-register-box {
  max-width: 400px;
}
</style>
