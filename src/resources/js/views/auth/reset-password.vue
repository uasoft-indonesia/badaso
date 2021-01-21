<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >
    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">{{ $t('resetPassword.title') }}</h3>
        <p class="mb-0">{{ $t('resetPassword.subtitle') }}</p>
      </div>
      <div>
        <form novalidate="novalidate">
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
          />
          <vs-button type="relief" class="btn-block">{{ $t('resetPassword.button') }}</vs-button>
        </form>

        <div class="d-flex justify-content-center mt-3">
          {{ $t('resetPassword.createAccount.text') }} &nbsp;
          <router-link :to="'/' + baseUrl + '/register'"
            >{{ $t('resetPassword.createAccount.link') }}</router-link
          >
        </div>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  data: () => ({
    email: "",
    password: "",
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
  }),
};
</script>

<style>
.login-register-box {
  max-width: 400px;
}
</style>
