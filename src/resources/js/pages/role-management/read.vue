<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="warning"
          type="relief"
          :to="{ name: 'RoleManagementEdit', params: { id: $route.params.id } }"
          v-if="$helper.isAllowed('edit_roles')"
          ><vs-icon icon="edit"></vs-icon>
          {{ $t("role.detail.button.edit") }}</vs-button
        >
        <vs-button
          color="primary"
          type="relief"
          @click.stop
          :to="{
            name: 'RoleManagementPermissions',
            params: { id: $route.params.id },
          }"
          v-if="$helper.isAllowed('browse_role_permission')"
          ><vs-icon icon="list"></vs-icon>
          {{ $t("role.detail.button.permission") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('read_roles')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("role.detail.title") }}</h3>
          </div>
          <table class="badaso-table">
            <tr>
              <th>{{ $t("role.detail.name") }}</th>
              <td>{{ role.name }}</td>
            </tr>
            <tr>
              <th>{{ $t("role.detail.displayName") }}</th>
              <td>{{ role.displayName }}</td>
            </tr>
            <tr>
              <th>{{ $t("role.detail.description") }}</th>
              <td>{{ role.description }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "RoleManagementRead",
  components: {},
  data: () => ({
    role: {},
  }),
  mounted() {
    this.getRoleDetail();
  },
  methods: {
    getRoleDetail() {
      this.$openLoader();
      this.$api.badasoRole
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.role = response.data.role;
        })
        .catch((error) => {
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
