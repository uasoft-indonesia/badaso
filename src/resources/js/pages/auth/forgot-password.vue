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

    <vs-card class="mb-0" v-if="!requestVerify">
      <div slot="header">
        <h3 class="mb-1">{{ $t("forgotPassword.title") }}</h3>
        <p class="mb-0">{{ $t("forgotPassword.subtitle") }}</p>
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
        <div v-if="errors.email" class="mb-4">
          <div v-if="$helper.isArray(errors.email)">
            <span
              class="text-danger"
              v-for="(info, index) in errors.email"
              :key="index"
              v-html="info"
            ></span>
          </div>
          <div v-else>
            <span class="text-danger" v-html="errors.email"></span>
          </div>
        </div>
        <vs-button type="relief" class="btn-block" @click="forgotPassword()">{{
          $t("forgotPassword.button")
        }}</vs-button>
      </div>
    </vs-card>

    <vs-card class="mb-0" v-else>
      <div slot="header">
        <h3 class="mb-1">{{ $t("verifyEmail.title") }}</h3>
      </div>
      <div>
        <vs-input
          icon="token"
          icon-after
          size="default"
          :placeholder="$t('verifyEmail.field.token')"
          v-model="token"
          class="w-100 mb-4 mt-2 "
          @keyup.enter="verify()"
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
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
    requestVerify: false,
    errors: {},
  }),
  methods: {
    forgotPassword() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.auth
        .forgotPassword({
          email: this.email,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.res = {
            status: "success",
            icon: "done",
            message: this.$t("forgotPassword.message.success"),
          };
          this.requestVerify = true;
        })
        .catch((error) => {
          this.$vs.loading.close();
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
      this.$vs.loading(this.$loadingConfig);
      this.$api.auth
        .forgotPasswordVerifyToken({
          email: this.email,
          token: this.token,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({
            name: "AuthResetPassword",
            query: {
              email: this.email,
              token: this.token,
            },
          });
        })
        .catch((error) => {
          this.$vs.loading.close();
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

<style>
.login-register-box {
  max-width: 400px;
}
</style>
