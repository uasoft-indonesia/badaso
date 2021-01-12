<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('browse_user_role')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Roles</h3>
          </div>
          <vs-table search :data="userRoles" stripe>
            <template slot="thead">
              <vs-th  v-if="$helper.isAllowed('add_or_edit_user_role')"> </vs-th>
              <vs-th> Name </vs-th>
              <vs-th> Description </vs-th>
              <vs-th> Action</vs-th>
            </template>

            <template slot-scope="{ data }">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td style="width: 1%"  v-if="$helper.isAllowed('add_or_edit_user_role')">
                  <vs-checkbox v-model="data[indextr].selected"></vs-checkbox>
                </vs-td>
                <vs-td
                  :data="data[indextr].displayName"
                  style="width: 1%; white-space: nowrap"
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
                      name: 'RoleRead',
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
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Set selected roles for user
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
    roles: [],
    userRoles: [],
  }),
  mounted() {
    this.getUserRoles();
  },
  methods: {
    getUserRoles() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.user
        .roles({
          userId: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.userRoles = [...response.data.userRoles];
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
    submitForm() {
      let selectedRoles = this.userRoles.filter(function(role) {
        return role.selected === 1 || role.selected === true;
      });
      selectedRoles = selectedRoles.map((role) => role.id);

      this.$vs.loading({
        type: "sound",
      });
      this.$api.user
        .addRoles({
          userId: this.$route.params.id,
          roles: selectedRoles,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getUserRoles();
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
