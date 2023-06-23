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
        $t("verifyEmail.title")
      }}</badaso-auth-card-header>
      <form novalidate="novalidate">
        <vs-input
          icon="lock"
          icon-after
          size="default"
          :placeholder="$t('verifyEmail.field.token')"
          v-model="token"
          class="verify__input"
        />
        <div v-if="errors.token" class="verify__error-container">
          <div v-if="$helper.isArray(errors.token)">
            <span
              class="verify__input--error"
              v-for="(info, index) in errors.token"
              :key="index"
            >
              {{ info }}
            </span>
          </div>
          <div v-else>
            <span class="verify__input--error" v-html="errors.token"></span>
          </div>
        </div>
        <vs-button type="relief" class="verify__button" @click="verify()">{{
          $t("verifyEmail.button")
        }}</vs-button>
        <div class="verify__resend">
          <span>Not receive email or token expired?</span>
          <vs-button
            v-if="retry || expired"
            type="relief"
            color="warning"
            class="verify__resend-button"
            @click="requestVerificationToken()"
            >{{ $t("verifyEmail.request") }}</vs-button
          >
          <span v-else class="verify__resend-button">{{ timeWait }}</span>
        </div>
      </form>
      <div class="verify__register-link">
        {{ $t("register.existingAccount.text") }} &nbsp;
        <router-link :to="'/' + baseUrl + '/login'">{{
          $t("register.existingAccount.link")
        }}</router-link>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  name: "AuthVerify",
  data: () => ({
    email: "",
    token: "",
    baseUrl: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-dashboard",
    errors: {},
    processing: true,
    expired: false,
    res: {
      active: false,
      icon: "",
      status: "",
      message: "",
    },
    retry: false,
    timeWait: 0,
  }),
  mounted() {
    this.email = this.$route.query.email;
    this.getConfigurationList();
  },
  methods: {
    getConfigurationList() {
      this.$api.badasoConfiguration
        .fetch({
          key: "timeWaitResendToken",
        })
        .then((response) => {
          this.timeWait = response.data.configuration[0].value;
          this.startCounter();
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    startCounter() {
      if (this.timeWait > 0) {
        setTimeout(() => {
          this.timeWait -= 1;
          this.startCounter();
        }, 1000);
      } else {
        this.retry = true;
      }
    },
    verify() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuth
        .verify({
          email: this.email,
          token: this.token,
        })
        .then((response) => {
          this.$closeLoader();
          this.$router.push({ name: "Home" });
        })
        .catch((error) => {
          this.processing = false;
          this.$closeLoader();
          if (error.message && error.message === "EXPIRED") {
            this.expired = true;
            this.errors = {
              token: ["Verification token expired"],
            };
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: "Verification token expired",
              color: "danger",
            });
          } else {
            this.errors = error.errors;
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }
        });
    },
    requestVerificationToken() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoAuth
        .reRequestVerificationToken({
          token: this.token,
          email: this.email,
        })
        .then((response) => {
          this.retry = false;
          this.timeWait = 60;
          this.startCounter();
          this.getConfigurationList();
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: response.data.message,
            color: "success",
          });
          this.res = {
            active: true,
            status: "success",
            icon: "done",
            message: this.$t("verifyEmail.message.success"),
          };
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
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
