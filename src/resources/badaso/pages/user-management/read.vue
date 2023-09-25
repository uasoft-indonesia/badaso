<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="warning"
          type="relief"
          :to="{ name: 'UserManagementEdit', params: { id: $route.params.id } }"
          v-if="$helper.isAllowed('edit_users')"
          ><vs-icon icon="edit"></vs-icon> {{ $t("action.edit") }}</vs-button
        >
        <vs-button
          color="primary"
          type="relief"
          @click.stop
          :to="{
            name: 'UserManagementRoles',
            params: { id: $route.params.id },
          }"
          v-if="$helper.isAllowed('browse_user_role')"
          ><vs-icon icon="list"></vs-icon> {{ $t("action.roles") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('read_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("user.detail.title") }}</h3>
          </div>
          <table class="badaso-table">
            <tr>
              <th>{{ $t("user.detail.avatar") }}</th>
              <td>
                <img :src="`${user.avatar}`" width="100%" alt="" />
              </td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.name") }}</th>
              <td>{{ user.name }}</td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.username") }}</th>
              <td>{{ user.username }}</td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.gender") }}</th>
              <td>{{ user.gender }}</td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.phone") }}</th>
              <td>{{ user.phone }}</td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.email") }}</th>
              <td>{{ user.email }}</td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.emailVerified") }}</th>
              <td>
                <span v-if="user.emailVerified">Yes</span>
                <span v-else>No</span>
              </td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.address") }}</th>
              <td>{{ user.address }}</td>
            </tr>
            <tr>
              <th>{{ $t("user.detail.additionalInfo") }}</th>
              <td>
                <pre>{{ user.additionalInfo }}</pre>
              </td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "UserManagementRead",
  components: {},
  data: () => ({
    user: {},
  }),
  mounted() {
    this.getUserDetail();
  },
  methods: {
    getUserDetail() {
      this.$openLoader();
      this.$api.badasoUser
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.user = response.data.user;
          this.user.additionalInfo = JSON.parse(this.user.additionalInfo);
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
