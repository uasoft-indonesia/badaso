<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_role_permission')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("menu.permission.title") }}</h3>
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
              <vs-th> {{ $t("menu.permission.header.key") }} </vs-th>
              <vs-th> {{ $t("menu.permission.header.description") }} </vs-th>
            </template>

            <template slot-scope="{ data }">
              <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                <vs-td
                  class="badaso-table__td"
                  v-if="$helper.isAllowed('edit_menu_items')"
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
    <vs-row v-if="$helper.isAllowed('edit_menu_items')">
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon>
                {{ $t("menu.permission.button") }}
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
              <h3>{{ $t("menu.warning.notAllowedToAdd") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "MenuPermissions",
  components: {},
  data: () => ({
    menuItemPermissions: [],
  }),
  mounted() {
    this.getMenuItemPermissions();
  },
  methods: {
    getMenuItemPermissions() {
      this.$openLoader();
      this.$api.badasoMenu
        .getItemPermissions({
          menuId: this.$route.params.id,
          menuItemId: this.$route.params.itemId,
        })
        .then((response) => {
          this.$closeLoader();
          this.menuItemPermissions = [...response.data.menuItemPermissions];
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    submitForm() {
      let selectedPermissions = this.menuItemPermissions.filter(function (
        permission
      ) {
        return permission.selected == 1 || permission.selected == true;
      });
      selectedPermissions = selectedPermissions.map(
        (permission) => permission.id
      );

      this.$openLoader();
      this.$api.badasoMenu
        .setItemPermissions({
          menuId: this.$route.params.id,
          menuItemId: this.$route.params.itemId,
          permissions: selectedPermissions,
        })
        .then((response) => {
          this.$closeLoader();
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
          this.$store.commit("badaso/FETCH_USER");
          this.getMenuItemPermissions();
          this.$vs.notify({
            title: "Success",
            text: "Permissions has been set",
            color: "success",
          });
        })
        .catch((error) => {
          this.$closeLoader();
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
