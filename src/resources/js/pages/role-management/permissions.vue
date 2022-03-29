<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_role_permission')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("role.permission.title") }}</h3>
          </div>
          <vs-table
            search
            :data="rolePermissions"
            stripe
            pagination
            max-items="10"
          >
            <template slot="thead">
              <vs-th v-if="$helper.isAllowed('add_or_edit_role_permission')">
              </vs-th>
              <vs-th> {{ $t("role.permission.header.key") }} </vs-th>
              <vs-th> {{ $t("role.permission.header.description") }} </vs-th>
            </template>

            <template slot-scope="{ data }">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td
                  class="badaso-table__td"
                  v-if="$helper.isAllowed('add_or_edit_role_permission')"
                >
                  <vs-checkbox v-model="data[indextr].selected"></vs-checkbox>
                </vs-td>
                <vs-td :data="data[indextr].key" class="badaso-table__td">
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
    <vs-row v-if="$helper.isAllowed('add_or_edit_role_permission')">
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon>
                {{ $t("role.permission.button") }}
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("role.warning.notAllowedToBrowsePermission") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "RoleManagementPermission",
  components: {},
  data: () => ({
    rolePermissions: [],
  }),
  mounted() {
    this.getRolePermissions();
  },
  methods: {
    getRolePermissions() {
      this.$openLoader();
      this.$api.badasoRole
        .permissions({
          roleId: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.rolePermissions = [...response.data.rolePermissions];
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
    submitForm() {
      let selectedPermissions = this.rolePermissions.filter(function (
        permission
      ) {
        return permission.selected == 1 || permission.selected == true;
      });
      selectedPermissions = selectedPermissions.map(
        (permission) => permission.id
      );

      this.$openLoader();
      this.$api.badasoRole
        .addPermissions({
          roleId: this.$route.params.id,
          permissions: selectedPermissions,
        })
        .then((response) => {
          this.$closeLoader();
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
          this.$store.commit("badaso/FETCH_USER");
          this.getRolePermissions();
          this.$vs.notify({
            title: this.$t("role.permission.success.title"),
            text: this.$t("role.permission.success.text"),
            color: "success",
          });
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
