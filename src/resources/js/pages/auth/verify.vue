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
        <h3 class="mb-1">{{ $t("verifyEmail.title") }}</h3>
      </div>
      <form novalidate="novalidate">
        <vs-input
          icon="lock"
          icon-after
          size="default"
          :placeholder="$t('verifyEmail.field.token')"
          v-model="token"
          class="w-100 mb-4 mt-2 "
        />
        <div v-if="errors.token" class="mb-4">
          <div v-if="$helper.isArray(errors.token)">
            <span
              class="text-danger"
              v-for="(info, index) in errors.token"
              :key="index"
              v-html="info+'<br />'"
            ></span>
          </div>
          <div v-else>
            <span class="text-danger" v-html="errors.token"></span>
          </div>
        </div>
        <vs-button type="relief" class="btn-block" @click="verify()">{{
          $t("verifyEmail.button")
        }}</vs-button>
        <div class="d-flex pt-3 pb-3">
          <span class="con-slot-label"
            >Not receive email or token expired?</span
          >
          <vs-button
            v-if="retry || expired"
            type="relief"
            color="warning"
            @click="requestVerificationToken()"
            class="ml-auto"
            >{{ $t("verifyEmail.request") }}</vs-button
          >
          <span v-else class="ml-auto">{{ timeWait }}</span>
        </div>
      </form>
      <div class="d-flex justify-content-center mt-3">
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
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
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
    timeWait: 60,
  }),
  mounted() {
    this.email = this.$route.query.email;
    this.startCounter();
  },
  methods: {
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
      this.$vs.loading(this.$loadingConfig);
      this.$api.auth
        .verify({
          email: this.email,
          token: this.token,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({ name: "Home" });
        })
        .catch((error) => {
          this.processing = false;
          this.$vs.loading.close();
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
      this.$vs.loading(this.$loadingConfig);
      this.$api.auth
        .reRequestVerificationToken({
          token: this.token,
          email: this.email,
        })
        .then((response) => {
          this.retry = false;
          this.timeWait = 60;
          this.startCounter();

          this.$vs.loading.close();
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
          this.$vs.loading.close();
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

<style>
.login-register-box {
  max-width: 400px;
}
</style>
