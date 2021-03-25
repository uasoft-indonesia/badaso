<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="warning"
          type="relief"
          :to="{ name: 'PermissionManagementEdit', params: { id: $route.params.id } }"
          v-if="$helper.isAllowed('edit_permissions')"
          ><vs-icon icon="edit"></vs-icon>
          {{ $t("permission.detail.button") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('read_permissions')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("permission.detail.title") }}</h3>
          </div>
          <table class="table">
            <tr>
              <th>{{ $t("permission.detail.key") }}</th>
              <td>{{ permission.key }}</td>
            </tr>
            <tr>
              <th>{{ $t("permission.detail.description") }}</th>
              <td>{{ permission.description }}</td>
            </tr>
            <tr>
              <th>{{ $t("permission.detail.tableName") }}</th>
              <td>{{ permission.tableName }}</td>
            </tr>
            <tr>
              <th>{{ $t("permission.detail.alwaysAllow.title") }}</th>
              <td>
                <span v-if="permission.alwaysAllow === 1">{{
                  $t("permission.detail.alwaysAllow.yes")
                }}</span>
                <span v-else>{{ $t("permission.detail.alwaysAllow.no") }}</span>
              </td>
            </tr>
            <tr>
              <th>{{ $t("permission.detail.isPublic.title") }}</th>
              <td>
                <span v-if="permission.isPublic === 1">{{
                  $t("permission.detail.isPublic.yes")
                }}</span>
                <span v-else>{{ $t("permission.detail.isPublic.no") }}</span>
              </td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("permission.warning.notAllowedToRead") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "PermissionManagementRead",
  components: {
  },
  data: () => ({
    permission: {},
  }),
  mounted() {
    this.getPermissionDetail();
  },
  methods: {
    getPermissionDetail() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.permission
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.permission = response.data.permission;
        })
        .catch((error) => {
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
