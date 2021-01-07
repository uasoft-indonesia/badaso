<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="warning" type="relief"
            :to="{name: 'RoleEdit', params: {id: $route.params.id}}"
            v-if="$helper.isAllowed('edit_roles')"
            ><vs-icon icon="edit"></vs-icon> Edit</vs-button
          >
          <vs-button
              color="primary"
              type="relief"
              @click.stop
              :to="{name: 'RolePermissions', params: {id: $route.params.id}}"
              v-if="$helper.isAllowed('browse_role_permission')"
              ><vs-icon icon="list"></vs-icon
            > Permissions</vs-button>
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('read_roles')">
      <vs-col vs-lg="12">
        <vs-card>
            <div slot="header">
            <h3>Detail Role</h3>
          </div>
            <table class="table">
                <tr>
                    <th>Name</th>
                    <td>{{ role.name }}</td>
                </tr>
                <tr>
                    <th>Display Name</th>
                    <td>{{ role.displayName }}</td>
                </tr>
                <tr>
                    <th>Description</th>
                    <td>{{ role.description }}</td>
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
      BadasoBreadcrumb
  },
  data: () => ({
    role: {}
  }),
  mounted() {
        this.getRoleDetail();
  },
  methods: {
    getRoleDetail() {
        this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .read({
            id: this.$route.params.id
        })
        .then((response) => {
          this.$vs.loading.close();
          this.role = response.data.role;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    }
  },
};
</script>