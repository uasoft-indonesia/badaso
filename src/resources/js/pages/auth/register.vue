<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >
    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">{{ $t("register.title") }}</h3>
        <p class="mb-0">{{ $t("register.subtitle") }}</p>
      </div>
      <div>
        <form novalidate="novalidate">
          <vs-input
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('register.field.name')"
            v-model="name"
            class="w-100 mb-4 mt-2 "
          />
          <div v-if="errors.name" class="mb-4">
            <div v-if="$helper.isArray(errors.name)">
              <span
                class="text-danger"
                v-for="(info, index) in errors.name"
                :key="index"
                v-html="info+'<br />'"
              ></span>
            </div>
            <div v-else>
              <span class="text-danger" v-html="errors.name"></span>
            </div>
          </div>
          <vs-input
            icon="email"
            icon-after
            size="default"
            :placeholder="$t('register.field.email')"
            v-model="email"
            class="w-100 mb-4 mt-2 "
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
          <vs-input
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('register.field.password')"
            v-model="password"
            class="w-100 mb-4 mt-2 "
          />
          <div v-if="errors.password" class="mb-4">
            <div v-if="$helper.isArray(errors.password)">
              <span
                class="text-danger"
                v-for="(info, index) in errors.password"
                :key="index"
                v-html="info"
              ></span>
            </div>
            <div v-else>
              <span class="text-danger" v-html="errors.password"></span>
            </div>
          </div>
          <vs-input
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('register.field.passwordConfirmation')"
            v-model="passwordConfirmation"
            class="w-100 mb-4 mt-2 "
          />
          <vs-button type="relief" class="btn-block" @click="register()">{{
            $t("register.button")
          }}</vs-button>
        </form>

        <div class="d-flex justify-content-center mt-3">
          {{ $t("register.existingAccount.text") }} &nbsp;
          <router-link :to="'/' + baseUrl + '/login'">{{
            $t("register.existingAccount.link")
          }}</router-link>
        </div>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  name: "AuthRegister",
  data: () => ({
    errors: {},
    name: "",
    email: "",
    password: "",
    passwordConfirmation: "",
    baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-admin",
  }),
  methods: {
    register() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.auth
        .register({
          name: this.name,
          email: this.email,
          password: this.password,
          passwordConfirmation: this.passwordConfirmation,
        })
        .then((response) => {
          this.$vs.loading.close();
          if (response.data.accessToken) {
            this.$router.push({ name: "Login" });
          } else {
            this.$router.push({
              name: "AuthVerify",
              query: {
                email: this.email,
              },
            });
          }
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
