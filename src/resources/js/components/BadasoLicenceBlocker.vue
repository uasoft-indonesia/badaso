<template>
  <vs-popup
    fullscreen
    :title="$t('keyIssue.title')"
    :active.sync="keyIssue.invalid"
    style="z-index: 26000"
    button-close-hidden	
  >
    <vs-row>
      <vs-col>
        <p>
          {{ $t("keyIssue.message") }}
        </p>
        <h3 style="text-align: center;">{{ keyIssue.message }}</h3>
        <br />
        <p>
          {{ $t("keyIssue.listTitle") }}
        </p>
        <ul>
          <li>
            <strong>{{ $t("keyIssue.licenceEmpty") }}</strong>
            <p>
              {{ $t("keyIssue.licenceEmptyDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
                ><vs-icon icon="open_in_new"></vs-icon
              ></a>
            </p>
          </li>
          <li>
            <strong>{{ $t("keyIssue.licenceInvalid") }}</strong>
            <p>
              {{ $t("keyIssue.licenceInvalidDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
                ><vs-icon icon="open_in_new"></vs-icon
              ></a>
            </p>
          </li>
          <li>
            <strong>{{ $t("keyIssue.licenceUsersExpired") }}</strong>
            <p>
              {{ $t("keyIssue.licenceUsersExpiredDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
                ><vs-icon icon="open_in_new"></vs-icon
              ></a>
            </p>
          </li>
        </ul>
      </vs-col>
    </vs-row>
  </vs-popup>
</template>

<script>
export default {
  name: "BadasoLicenceBlocker",
  components: {},
  data: () => ({}),
  mounted() {
    this.$store.commit("badaso/SET_KEY_ISSUE", {
      invalid: false
    });
  },
  computed: {
    keyIssue: {
      get() {
        return this.$store.state.badaso.keyIssue;
      },
    },
  },
};
</script>
