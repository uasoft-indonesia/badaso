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
            <strong>{{ $t("keyIssue.licenseEmpty") }}</strong>
            <p>
              {{ $t("keyIssue.licenseEmptyDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
                ><vs-icon icon="open_in_new"></vs-icon
              ></a>
            </p>
          </li>
          <li>
            <strong>{{ $t("keyIssue.licenseInvalid") }}</strong>
            <p>
              {{ $t("keyIssue.licenseInvalidDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
                ><vs-icon icon="open_in_new"></vs-icon
              ></a>
            </p>
          </li>
          <li>
            <strong>{{ $t("keyIssue.licenseUsersExpired") }}</strong>
            <p>
              {{ $t("keyIssue.licenseUsersExpiredDescription") }}
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
  name: "BadasoLicenseBlocker",
  components: {},
  data: () => ({}),
  mounted() {
    this.$store.commit("badaso/SET_KEY_ISSUE", {
      invalid: false,
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
