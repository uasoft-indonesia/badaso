<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_user_role')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("user.roles.title") }}</h3>
          </div>
          <vs-table search :data="userRoles" stripe>
            <template slot="thead">
              <vs-th v-if="$helper.isAllowed('add_or_edit_user_role')"> </vs-th>
              <vs-th> {{ $t("user.roles.header.name") }} </vs-th>
              <vs-th> {{ $t("user.roles.header.description") }} </vs-th>
              <vs-th> {{ $t("user.roles.header.action") }} </vs-th>
            </template>

            <template slot-scope="{ data }">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td
                  class="badaso-table__td"
                  v-if="$helper.isAllowed('add_or_edit_user_role')"
                >
                  <vs-checkbox v-model="data[indextr].selected"></vs-checkbox>
                </vs-td>
                <vs-td
                  :data="data[indextr].displayName"
                  class="badaso-table__td"
                >
                  {{ data[indextr].displayName }}
                </vs-td>
                <vs-td :data="data[indextr].description">
                  {{ data[indextr].description }}
                </vs-td>
                <vs-td style="width: 1%; white-space: nowrap">
                  <vs-button
                    color="success"
                    type="relief"
                    @click.stop
                    :to="{
                      name: 'RoleManagementRead',
                      params: { id: data[indextr].id },
                    }"
                    v-if="$helper.isAllowed('read_role')"
                    ><vs-icon icon="visibility"></vs-icon
                  ></vs-button>
                </vs-td>
              </vs-tr>
            </template>
          </vs-table>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_or_edit_user_role')">
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t("user.roles.button") }}
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
export default {
  name: "UserManagementRoles",
  components: {},
  data: () => ({
    roles: [],
    userRoles: [],
  }),
  mounted() {
    this.getUserRoles();
  },
  methods: {
    getUserRoles() {
      this.$openLoader();
      this.$api.badasoUser
        .roles({
          userId: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.userRoles = [...response.data.userRoles];
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
      let selectedRoles = this.userRoles.filter(function (role) {
        return role.selected == 1 || role.selected == true;
      });
      selectedRoles = selectedRoles.map((role) => role.id);

      this.$openLoader();
      this.$api.badasoUser
        .addRoles({
          userId: this.$route.params.id,
          roles: selectedRoles,
        })
        .then((response) => {
          this.$closeLoader();
          this.getUserRoles();
          this.$vs.notify({
            title: this.$t("user.roles.success.title"),
            text: this.$t("user.roles.success.text"),
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
