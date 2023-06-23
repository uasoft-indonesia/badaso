<template>
  <vs-prompt
    :title="$t('keyIssue.title')"
    :active.sync="show"
    buttons-hidden
    class="badaso-license-blocker__container"
    @close="show = true"
  >
    <vs-row>
      <vs-col>
        <p>{{ $t("keyIssue.message") }}</p>
        <h3 class="badaso-license-blocker__title">
          {{ keyIssue.message }}
        </h3>
        <br />
        <p>{{ $t("keyIssue.listTitle") }}</p>
        <ul>
          <li>
            <strong>{{ $t("keyIssue.licenseEmpty") }}</strong>
            <p>
              {{ $t("keyIssue.licenseEmptyDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
              >
                <vs-icon icon="open_in_new" />
              </a>
            </p>
          </li>
          <li>
            <strong>{{ $t("keyIssue.licenseInvalid") }}</strong>
            <p>
              {{ $t("keyIssue.licenseInvalidDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
              >
                <vs-icon icon="open_in_new" />
              </a>
            </p>
          </li>
          <li>
            <strong>{{ $t("keyIssue.licenseUsersExpired") }}</strong>
            <p>
              {{ $t("keyIssue.licenseUsersExpiredDescription") }}
              <a
                href="https://badaso-docs.uatech.co.id/docs/en/getting-started/installation/"
                target="_blank"
              >
                <vs-icon icon="open_in_new" />
              </a>
            </p>
          </li>
        </ul>
      </vs-col>
    </vs-row>
  </vs-prompt>
</template>

<script>
export default {
  name: "BadasoLicenseBlocker",
  components: {},
  data: () => ({
    show: true,
  }),
  computed: {
    keyIssue: {
      get() {
        return this.$store.state.badaso.keyIssue;
      },
    },
  },
  mounted() {},
};
</script>
