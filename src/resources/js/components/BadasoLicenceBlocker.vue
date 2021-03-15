<template>
    <vs-popup fullscreen :title="$t('licenceIssue.title')" :active.sync="licenceIssue.invalid" style="z-index: 26000">
      <vs-row>
        <vs-col>
        <p>
        {{ $t("licenceIssue.message") }}
      </p>
      <h3 style="text-align: center;">{{licenceIssue.message}}</h3>
      <br />
      <p>
        {{ $t("licenceIssue.listTitle") }}
      </p>
      <ul>
        <li>
            <strong>{{ $t("licenceIssue.licenceEmpty") }}</strong>
            <p>
                {{ $t("licenceIssue.licenceEmptyDescription") }}
                <a href="" target="_blank"><vs-icon icon="open_in_new"></vs-icon></a>
            </p>
        </li>
        <li>
            <strong>{{ $t("licenceIssue.licenceInvalid") }}</strong>
            <p>
                {{ $t("licenceIssue.licenceInvalidDescription") }}
                <a href="" target="_blank"><vs-icon icon="open_in_new"></vs-icon></a>
            </p>
        </li>
        <li>
            <strong>{{ $t("licenceIssue.licenceUsersExpired") }}</strong>
            <p>
                {{ $t("licenceIssue.licenceUsersExpiredDescription") }}
                <a href="" target="_blank"><vs-icon icon="open_in_new"></vs-icon></a>
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
    this.$store.commit("SET_LICENCE_ISSUE", false);
  },
  computed: {
    licenceIssue: {
      get() {
        return this.$store.state.licenceIssue;
      }
    }
  },
};
</script>