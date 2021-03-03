<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >
    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">{{ $t('login.title') }}</h3>
        <p class="mb-0">{{ $t('login.subtitle') }}</p>
      </div>
      <div>
        <form @submit="login()">
          <vs-input
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('login.field.email')"
            v-model="email"
            class="w-100 mb-4 mt-2 "
          />
          <vs-input
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('login.field.password')"
            v-model="password"
            class="w-100 mb-4 mt-2 "
            @keyup.enter="login()"
          />

          <div class="d-flex pt-3 pb-3">
            <div
              class="vs-component con-vs-checkbox vs-checkbox-primary vs-checkbox-default"
            >
              <input
                type="checkbox"
                class="vs-checkbox--input"
                value="false"
              /><span
                class="checkbox_x vs-checkbox"
                style="border: 2px solid rgb(180, 180, 180);"
                ><span class="vs-checkbox--check"
                  ><i
                    class="vs-icon notranslate icon-scale vs-checkbox--icon  material-icons null"
                    >check</i
                  ></span
                ></span
              ><span class="con-slot-label">{{ $t('login.rememberMe') }}</span>
            </div>
            <router-link
              :to="'/' + baseUrl + '/forgot-password'"
              class="ml-auto"
              >{{ $t('login.forgotPassword') }}</router-link
            >
          </div>
          <vs-button type="relief" class="btn-block" @click="login()">{{ $t('login.button') }}</vs-button>
        </form>
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
  methods: {
    login() {
      this.$vs.loading({
        type:'sound',
      })
      this.$api.auth.login({
        email: this.email,
        password: this.password
      })
      .then((response) => {
        this.$vs.loading.close()
        this.$router.push({ name: 'Home'})
      })
      .catch((error) => {
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
