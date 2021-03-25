<template>
  <vs-popup
    :title="$t('authorizationIssue.title')"
    :active.sync="unauthorize"
    style="z-index: 26000"
  >
    <vs-row>
      <vs-col>
        <p>
          {{ $t("authorizationIssue.subtitle") }}
        </p>
				<p>
        <h3 style="text-align: center;">
          {{ $t("authorizationIssue.message") }}
        </h3>
				</p>
      </vs-col>
    </vs-row>
		<vs-row>
			<vs-col style="text-align: center">
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
		}
	}
};
</script>
