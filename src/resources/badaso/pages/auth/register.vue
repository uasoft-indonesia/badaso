<template>
  <vs-col vs-lg="12" class="main-container__box--auth">
    <vs-card class="main-container__card--auth">
      <badaso-auth-card-header slot="header">{{
        $t("register.title")
      }}</badaso-auth-card-header>
      <div>
        <form novalidate="novalidate">
          <vs-input
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('register.field.name')"
            v-model="name"
            class="register__input"
          />
          <div v-if="errors.name" class="register__error-container">
            <div v-if="$helper.isArray(errors.name)">
              <span
                class="register__input--error"
                v-for="(info, index) in errors.name"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.name"></span>
            </div>
          </div>
          <vs-input
            icon="person"
            icon-after
            size="default"
            :placeholder="$t('register.field.username')"
            v-model="username"
            class="register__input"
          />
          <div v-if="errors.username" class="register__error-container">
            <div v-if="$helper.isArray(errors.username)">
              <span
                class="register__input--error"
                v-for="(info, index) in errors.username"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span
                class="register__input--error"
                v-html="errors.username"
              ></span>
            </div>
          </div>
          <vs-input
            icon="phone"
            icon-after
            size="default"
            :placeholder="$t('register.field.phone')"
            v-model="phone"
            class="register__input"
          />
          <div v-if="errors.phone" class="register__error-container">
            <div v-if="$helper.isArray(errors.phone)">
              <span
                class="register__input--error"
                v-for="(info, index) in errors.phone"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.phone"></span>
            </div>
          </div>
          <vs-input
            icon="email"
            icon-after
            size="default"
            :placeholder="$t('register.field.email')"
            v-model="email"
            class="register__input"
          />
          <div v-if="errors.email" class="register__error-container">
            <div v-if="$helper.isArray(errors.email)">
              <span
                class="register__input--error"
                v-for="(info, index) in errors.email"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.email"></span>
            </div>
          </div>
          <vs-input
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('register.field.password')"
            v-model="password"
            class="register__input"
          />
          <div v-if="errors.password" class="register__error-container">
            <div v-if="$helper.isArray(errors.password)">
              <span
                class="register__input--error"
                v-for="(info, index) in errors.password"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span
                class="register__input--error"
                v-html="errors.password"
              ></span>
            </div>
          </div>
          <vs-input
            icon="lock"
            type="password"
            icon-after
            size="default"
            :placeholder="$t('register.field.passwordConfirmation')"
            v-model="passwordConfirmation"
            class="register__input"
          />
          <vs-input
            icon="place"
            icon-after
            size="default"
            :placeholder="$t('register.field.address')"
            v-model="address"
            class="register__input"
          />
          <div v-if="errors.address" class="register__error-container">
            <div v-if="$helper.isArray(errors.address)">
              <span
                class="register__input--error"
                v-for="(info, index) in errors.address"
                :key="index"
              >
                {{ info }}
              </span>
            </div>
            <div v-else>
              <span class="register__input--error" v-html="errors.address"></span>
            </div>
          </div>
          <badaso-select
              v-model="gender"
              :placeholder="$t('register.field.gender')"
              :items="genderitems"
              :alert="errors.gender"
           ></badaso-select>
          <vs-button
            type="relief"
            class="register__button"
            @click="register()"
            >{{ $t("register.button") }}</vs-button
          >
        </form>

        <div class="register__login-link">
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
  data() {
    return {
        errors: {},
        name: "",
        username: "",
        phone:"",
        address:"",
        gender:"",
        email: "",
        password: "",
        passwordConfirmation: "",
        baseUrl: import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
            ? import.meta.env.VITE_ADMIN_PANEL_ROUTE_PREFIX
            : "badaso-dashboard",

        genderitems:[
            { label: this.$t("register.gender.man"), value: "man" },
            { label: this.$t("register.gender.woman"), value: "woman" },
        ]
    };
  },
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
          address:this.address,
          gender: this.gender,
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
