<template>
  <vs-col vs-lg="12" class="main-container__box--auth">
    <vs-card class="main-container__card--auth">
      <badaso-auth-card-header slot="header">
        {{ $t("register.title") }}
      </badaso-auth-card-header>
      <div>
        <form novalidate="novalidate">
          <vs-input
            v-model="name"
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('register.field.name')"
            class="register__input"
          />
          <div v-if="errors.name" class="register__error-container">
            <div v-if="$helper.isArray(errors.name)">
              <span
                v-for="(info, index) in errors.name"
                :key="index"
                class="register__input--error"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.name" />
            </div>
          </div>
          <vs-input
            v-model="username"
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('register.field.username')"
            class="register__input"
          />
          <div v-if="errors.username" class="register__error-container">
            <div v-if="$helper.isArray(errors.username)">
              <span
                v-for="(info, index) in errors.username"
                :key="index"
                class="register__input--error"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.username" />
            </div>
          </div>
          <vs-input
            v-model="phone"
            icon="phone"
            icon-after
            size="default"
            :placeholder="$t('register.field.phone')"
            class="register__input"
          />
          <div v-if="errors.phone" class="register__error-container">
            <div v-if="$helper.isArray(errors.phone)">
              <span
                v-for="(info, index) in errors.phone"
                :key="index"
                class="register__input--error"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.phone" />
            </div>
          </div>
          <vs-input
            v-model="email"
            icon="email"
            icon-after
            size="default"
            :placeholder="$t('register.field.email')"
            class="register__input"
          />
          <div v-if="errors.email" class="register__error-container">
            <div v-if="$helper.isArray(errors.email)">
              <span
                v-for="(info, index) in errors.email"
                :key="index"
                class="register__input--error"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.email" />
            </div>
          </div>
          <vs-input
            v-model="password"
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('register.field.password')"
            class="register__input"
          />
          <div v-if="errors.password" class="register__error-container">
            <div v-if="$helper.isArray(errors.password)">
              <span
                v-for="(info, index) in errors.password"
                :key="index"
                class="register__input--error"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.password" />
            </div>
          </div>
          <vs-input
            v-model="passwordConfirmation"
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('register.field.passwordConfirmation')"
            class="register__input"
          />
          <vs-button type="relief" class="register__button" @click="register()">
            {{ $t("register.button") }}
          </vs-button>
        </form>

        <div class="register__login-link">
          {{ $t("register.existingAccount.text") }} &nbsp;
          <router-link :to="'/' + baseUrl + '/login'">
            {{ $t("register.existingAccount.link") }}
          </router-link>
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
    username: "",
    phone: "",
    email: "",
    password: "",
    passwordConfirmation: "",
    baseUrl: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
      : "badaso-dashboard",
  }),
  methods: {
    register() {
      this.$openLoader();
      this.$api.badasoAuth
        .register({
          name: this.name,
          username: this.username,
          phone: this.phone,
          email: this.email,
          password: this.password,
          passwordConfirmation: this.passwordConfirmation,
        })
        .then((response) => {
          this.$closeLoader();
          if (response.data.accessToken) {
            this.$router.push({ name: "AuthLogin" });
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
