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

    <vs-card class="main-container__card--auth" v-if="!requestVerify">
      <badaso-auth-card-header slot="header">{{
        $t("forgotPassword.title")
      }}</badaso-auth-card-header>
      <div>
        <vs-input
          icon="email"
          icon-after
          size="default"
          :placeholder="$t('forgotPassword.field.email')"
          v-model="email"
          class="forgot-password__input"
          @keyup.enter="forgotPassword()"
        />
        <div v-if="errors.email" class="forgot-password__error-container">
          <div v-if="$helper.isArray(errors.email)">
            <span
              class="forgot-password__input--error"
              v-for="(info, index) in errors.email"
              :key="index"
            >
              {{ info }}
            </span>
          </div>
          <div v-else>
            <span
              class="forgot-password__input--error"
              v-html="errors.email"
            ></span>
          </div>
        </div>
        <vs-button
          type="relief"
          class="forgot-password__button"
          @click="forgotPassword()"
          >{{ $t("forgotPassword.button") }}</vs-button
        >
      </div>
    </vs-card>

    <vs-card class="main-container__card--auth" v-else>
      <div slot="header">
        <h3 class="forgot-password__title">{{ $t("verifyEmail.title") }}</h3>
      </div>
      <div>
        <vs-input
          icon="token"
          icon-after
          size="default"
          :placeholder="$t('verifyEmail.field.token')"
          v-model="token"
          class="forgot-password__input"
          @keyup.enter="verify()"
        />
        <div v-if="errors.token" class="forgot-password__error-container">
          <div v-if="$helper.isArray(errors.token)">
            <span
              class="forgot-password__input--error"
              v-for="(info, index) in errors.token"
              :key="index"
            >
              {{ info }}
            </span>
          </div>
          <div v-else>
            <span
              class="forgot-password__input--error"
              v-html="errors.token"
            ></span>
          </div>
        </div>
        <vs-button
          type="relief"
          class="forgot-password__button"
          @click="verify()"
          >{{ $t("verifyEmail.button") }}</vs-button
        >
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  name: "AuthForgotPassword",
  data: () => ({
    email: "",
    password: "",
    token: "",
    res: {
      active: false,
      icon: "",
      status: "",
      message: "",
    },
    baseUrl: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-dashboard",
    requestVerify: false,
    errors: {},
  }),
  methods: {
    forgotPassword() {
      this.$openLoader();
      this.$api.badasoAuth
        .forgotPassword({
          email: this.email,
        })
        .then((response) => {
          this.$closeLoader();
          this.res = {
            status: "success",
            icon: "done",
            message: this.$t("forgotPassword.message.success"),
          };
          this.requestVerify = true;
        })
        .catch((error) => {
          this.$closeLoader();
          this.errors = error.errors;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    verify() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuth
        .forgotPasswordVerifyToken({
          email: this.email,
          token: this.token,
        })
        .then((response) => {
          this.$closeLoader();
          this.$router.push({
            name: "AuthResetPassword",
            query: {
              email: this.email,
              token: this.token,
            },
          });
        })
        .catch((error) => {
          this.$closeLoader();
          this.errors = error.errors;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
