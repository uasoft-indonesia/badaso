<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_role_permission')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Permissions</h3>
          </div>
          <vs-table
            search
            :data="menuItemPermissions"
            stripe
            pagination
            max-items="10"
          >
            <template slot="thead">
              <vs-th v-if="$helper.isAllowed('edit_menu_items')"> </vs-th>
              <vs-th> Key </vs-th>
              <vs-th> Description </vs-th>
            </template>

            <template slot-scope="{ data }">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td
                  style="width: 1%"
                  v-if="$helper.isAllowed('edit_menu_items')"
                >
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
    <vs-row v-if="$helper.isAllowed('edit_menu_items')">
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Set selected permissions for
                menu item
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
              <h3>You're not allowed to browse Menu Item Permissions</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
// import BadasoBreadcrumbRow from "../../components/BadasoBreadcrumbRow.vue";

export default {
  name: "MenuPermissions",
  components: {
    // BadasoBreadcrumbRow,
  },
  data: () => ({
    menuItemPermissions: [],
  }),
  mounted() {
    this.getMenuItemPermissions();
  },
  methods: {
    getMenuItemPermissions() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.menu
        .getItemPermissions({
          menuId: this.$route.params.id,
          menuItemId: this.$route.params.itemId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.menuItemPermissions = [...response.data.menuItemPermissions];
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
      let selectedPermissions = this.menuItemPermissions.filter(function(
        permission
      ) {
        return permission.selected === 1 || permission.selected === true;
      });
      selectedPermissions = selectedPermissions.map(
        (permission) => permission.id
      );

      this.$vs.loading(this.$loadingConfig);
      this.$api.menu
        .setItemPermissions({
          menuId: this.$route.params.id,
          menuItemId: this.$route.params.itemId,
          permissions: selectedPermissions,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.$store.commit("FETCH_MENU");
          this.$store.commit("FETCH_CONFIGURATION_MENU");
          this.$store.commit("FETCH_USER");
          this.getMenuItemPermissions();
          this.$vs.notify({
            title: "Success",
            text: "Permissions has been set",
            color: "success",
          });
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
