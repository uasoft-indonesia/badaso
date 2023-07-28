<template>
  <vs-col vs-lg="12" class="main-container__box--auth">
    <vs-card class="main-container__card--auth">
      <badaso-auth-card-header slot="header">{{
        $t("login.title")
      }}</badaso-auth-card-header>
      <div>
        <form @submit="login()">
          <vs-input
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('login.field.email')"
            v-model="email"
            class="login__input"
          />
          <div v-if="errors.email" class="login__error-container">
            <div v-if="$helper.isArray(errors.email)">
              <span
                class="login__input--error"
                v-for="(info, index) in errors.email"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="login__input--error" v-html="errors.email"></span>
            </div>
          </div>
          <vs-input
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('login.field.password')"
            v-model="password"
            class="login__input"
            @keyup.enter="login()"
          />
          <div v-if="errors.password" class="login__error-container">
            <div v-if="$helper.isArray(errors.password)">
              <span
                class="login__input--error"
                v-for="(info, index) in errors.password"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="login__input--error" v-html="errors.password"></span>
            </div>
          </div>

          <div class="login__footer">
            <div
              class="vs-component con-vs-checkbox vs-checkbox-primary vs-checkbox-default"
            >
              <input
                type="checkbox"
                class="vs-checkbox--input"
                v-model="rememberMe"
              /><span
                class="checkbox_x vs-checkbox"
                style="border: 2px solid rgb(180, 180, 180)"
                ><span class="vs-checkbox--check"
                  ><i
                    class="vs-icon notranslate icon-scale vs-checkbox--icon material-icons null"
                    >check</i
                  ></span
                ></span
              ><span class="con-slot-label">{{ $t("login.rememberMe") }}</span>
            </div>
            <router-link
              :to="'/' + baseUrl + '/forgot-password'"
              class="login__forgot-password"
              >{{ $t("login.forgotPassword") }}</router-link
            >
          </div>
          <vs-button type="relief" class="login__button" @click="login()">{{
            $t("login.button")
          }}</vs-button>
        </form>
        <div class="login__register-link">
          {{ $t("login.createAccount.text") }} &nbsp;
          <router-link :to="'/' + baseUrl + '/register'">{{
            $t("login.createAccount.link")
          }}</router-link>
        </div>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
  name: "AuthLogin",
  data: () => ({
    email: "",
    password: "",
    baseUrl: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-dashboard",
    rememberMe: false,
    errors: {},
  }),
  methods: {
    login() {
      this.$openLoader();
      this.$store.commit("badaso/SET_AUTH_ISSUE", {
        unauthorized: false,
      });
      this.$api.badasoAuth
        .login({
          email: this.email,
          password: this.password,
          remember: this.rememberMe,
        })
        .then((response) => {
          this.$closeLoader();
          if (response.hasOwnProperty("data")) {
            this.$router.push({ name: "Home" });
          } else {
            this.$router.push({
              name: "AuthVerify",
              query: {
                email: this.email,
              },
            });
          }
          if (this.$statusActiveFeatureFirebase) {
            this.$messagingToken.then((tokenMessage) => {
              try {
                this.$api.badasoFcm.saveTokenMessage(tokenMessage);
              } catch (error) {
                console.error(
                  "Errors set token firebase cloud message :",
                  error
                );
              }
            });
          }
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
