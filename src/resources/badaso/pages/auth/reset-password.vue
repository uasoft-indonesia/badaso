<template>
  <vs-col vs-lg="12" class="main-container__box--auth">
    <vs-alert
      :active="res.active"
      :color="res.status"
      :icon="res.icon"
      class="main-container__alert--auth"
    >
      <span>{{ res.message }}</span>
    </vs-alert>

    <vs-card class="main-container__card--auth">
      <badaso-auth-card-header slot="header">{{
        $t("resetPassword.title")
      }}</badaso-auth-card-header>
      <div>
        <vs-input
          icon="lock"
          type="password"
          icon-after
          size="default"
          :placeholder="$t('resetPassword.field.password')"
          v-model="password"
          class="reset-password__input"
        />
        <vs-input
          icon="lock"
          type="password"
          icon-after
          size="default"
          :placeholder="$t('resetPassword.field.passwordConfirmation')"
          v-model="passwordConfirmation"
          class="reset-password__input"
          @keyup.enter="resetPassword()"
        />
        <vs-button
          type="relief"
          class="reset-password__button"
          @click="resetPassword()"
          >{{ $t("resetPassword.button") }}</vs-button
        >
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
    baseUrl: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-dashboard",
    res: {
      active: false,
      icon: "",
      status: "",
      message: "",
    },
  }),
  methods: {
    resetPassword() {
      this.$openLoader();
      this.$api.badasoAuth
        .resetPassword({
          email: this.$router.currentRoute.query.email,
          token: this.$router.currentRoute.query.token,
          password: this.password,
          password_confirmation: this.passwordConfirmation,
        })
        .then((response) => {
          this.$closeLoader();
          this.res = {
            status: "success",
            icon: "done",
            message: this.$t("resetPassword.message.success"),
          };
          setTimeout(() => {
            this.$router.push({ name: "AuthLogin" });
          }, 5000);
        })
        .catch(() => {
          this.$closeLoader();
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
