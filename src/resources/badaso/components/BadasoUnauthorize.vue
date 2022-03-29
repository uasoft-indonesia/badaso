<template>
  <vs-popup
    :title="$t('authorizationIssue.title')"
    :active.sync="unauthorize"
    class="badaso-unauthorize__container"
  >
    <vs-row>
      <vs-col>
        <p class="badaso-unauthorize__title">
          {{ $t("authorizationIssue.subtitle") }}
        </p>
        <div>
          <h3 class="badaso-unauthorize__message">
            {{ $t("authorizationIssue.message") }}
          </h3>
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col class="badaso-unauthorize__button">
        <vs-button type="relief" @click="login()">{{
          $t("login.button")
        }}</vs-button>
      </vs-col>
    </vs-row>
  </vs-popup>
</template>

<script>
export default {
  name: "BadasoUnauthorize",
  components: {},
  data: () => ({}),
  mounted() {
    this.$store.commit("badaso/SET_AUTH_ISSUE", {
      unauthorized: false,
    });
  },
  computed: {
    unauthorize: {
      get() {
        return this.$store.state.badaso.authorizationIssue
          ? this.$store.state.badaso.authorizationIssue.unauthorized
          : false;
      },
      set(val) {
        if (val === false) {
          this.$store.commit("badaso/LOGOUT");
        }
      },
    },
    authorizationIssue: {
      get() {
        return this.$store.state.badaso.authorizationIssue;
      },
    },
  },
  methods: {
    login() {
      this.$store.commit("badaso/LOGOUT");
    },
  },
};
</script>
