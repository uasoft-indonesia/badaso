<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >
    <vs-alert
      :active="res.active"
      :color="res.status"
      :icon="res.icon"
      class="mb-2"
    >
      <span>{{ res.message }}</span>
    </vs-alert>

    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">{{ $t("resetPassword.title") }}</h3>
        <p class="mb-0">{{ $t("resetPassword.subtitle") }}</p>
      </div>
      <div>
        <vs-input
          icon="lock"
          type="password"
          icon-after
          size="default"
          :placeholder="$t('resetPassword.field.password')"
          v-model="password"
          class="w-100 mb-4 mt-2 "
        />
        <vs-input
          icon="lock"
          type="password"
          icon-after
          size="default"
          :placeholder="$t('resetPassword.field.passwordConfirmation')"
          v-model="passwordConfirmation"
          class="w-100 mb-4 mt-2 "
          @keyup.enter="resetPassword()"
        />
        <vs-button type="relief" class="btn-block" @click="resetPassword()">{{
          $t("resetPassword.button")
        }}</vs-button>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  name: "AuthResetPassword",
  data: () => ({
    password: "",
    passwordConfirmation: "",
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
    res: {
      active: false,
      icon: "",
      status: "",
      message: "",
    },
  }),
  methods: {
    resetPassword() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.auth
        .resetPassword({
          email: this.$router.currentRoute.query.email,
          token: this.$router.currentRoute.query.token,
          password: this.password,
          password_confirmation: this.passwordConfirmation,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.res = {
            status: "success",
            icon: "done",
            message: this.$t("resetPassword.message.success"),
          };
          setTimeout(() => {
            this.$router.push({ name: "AuthLogin" });
          }, 5000);
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.res = {
            status: "danger",
            icon: "dangerous",
            message: this.$t("resetPassword.message.error"),
          };
        });
    },
  },
};
</script>

<style>
.login-register-box {
  max-width: 400px;
}
</style>
