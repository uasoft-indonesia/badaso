<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button
            color="warning"
            type="relief"
            :to="{ name: 'UserEdit', params: { id: $route.params.id } }"
            v-if="$helper.isAllowed('edit_users')"
            ><vs-icon icon="edit"></vs-icon> Edit</vs-button
          >
          <vs-button
            color="primary"
            type="relief"
            @click.stop
            :to="{ name: 'UserRoles', params: { id: $route.params.id } }"
            v-if="$helper.isAllowed('browse_user_role')"
            ><vs-icon icon="list"></vs-icon> Roles</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('read_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Detail User</h3>
          </div>
          <table class="table">
            <tr>
              <th>Avatar</th>
              <td>
                <img
                  :src="`${$api.file.view(user.avatar)}`"
                  width="100%"
                  alt=""
                />
              </td>
            </tr>
            <tr>
              <th>Name</th>
              <td>{{ user.name }}</td>
            </tr>
            <tr>
              <th>Email</th>
              <td>{{ user.email }}</td>
            </tr>
            <tr>
              <th>Additional Info</th>
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
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";

export default {
  name: "Browse",
  components: {
    BadasoBreadcrumb,
  },
  data: () => ({
    user: {},
  }),
  mounted() {
    this.getUserDetail();
  },
  methods: {
    getUserDetail() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.user
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.user = response.data.user;
          this.user.additionalInfo = JSON.parse(this.user.additionalInfo)
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
