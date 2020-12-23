<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Permissions</h3>
          </div>
          <vs-table
            search
            :data="rolePermissions"
            stripe
            pagination
            max-items="10"
        >
            <template slot="thead">
              <vs-th> </vs-th>
              <vs-th> Key </vs-th>
              <vs-th> Description </vs-th>
            </template>

            <template slot-scope="{ data }">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td style="width: 1%">
                  <vs-checkbox v-model="data[indextr].selected"></vs-checkbox>
                </vs-td>
                <vs-td
                  :data="data[indextr].key"
                  style="width: 1%; white-space: nowrap"
                >
                  {{ data[indextr].key }}
                </vs-td>
                <vs-td :data="data[indextr].description">
                  {{ data[indextr].description }}
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Set selected permissions for role
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";

export default {
  name: "Roles",
  components: {
    BadasoBreadcrumb,
  },
  data: () => ({
    rolePermissions: [],
  }),
  mounted() {
    this.getRolePermissions();
  },
  methods: {
    getRolePermissions() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .permissions({
          roleId: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.rolePermissions = [...response.data];
        })
        .catch((error) => {
          console.log(error);
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    submitForm() {
      let selectedPermissions = this.rolePermissions.filter(function(permission) {
        return permission.selected === 1 || permission.selected === true
      });
      selectedPermissions = selectedPermissions.map((permission) => permission.id);

      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .addPermissions({
          roleId: this.$route.params.id,
          permissions: selectedPermissions,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getRolePermissions();
        })
        .catch((error) => {
          console.log(error);
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
