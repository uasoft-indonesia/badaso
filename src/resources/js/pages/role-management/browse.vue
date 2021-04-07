<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'RoleManagementAdd' }"
          v-if="$helper.isAllowed('add_roles')"
          ><vs-icon icon="add"></vs-icon> {{ $t("action.add") }}</vs-button
        >
        <vs-button
          color="danger"
          type="relief"
          v-if="selected.length > 0 && $helper.isAllowed('delete_roles')"
          @click.stop
          @click="confirmDeleteMultiple"
          ><vs-icon icon="delete_sweep"></vs-icon>
          {{ $t("action.bulkDelete") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_roles')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("role.title") }}</h3>
          </div>
          <div>
            <badaso-table
              multiple
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="roles"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('role.footer.descriptionTitle')"
              :description-connector="$t('role.footer.descriptionConnector')"
              :description-body="$t('role.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="name"> {{ $t("role.header.name") }} </vs-th>
                <vs-th sort-key="displayName">
                  {{ $t("role.header.displayName") }}
                </vs-th>
                <vs-th sort-key="description">
                  {{ $t("role.header.description") }}
                </vs-th>
                <vs-th> {{ $t("role.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].name">
                    {{ data[indextr].name }}
                  </vs-td>
                  <vs-td :data="data[indextr].displayName">
                    {{ data[indextr].displayName }}
                  </vs-td>
                  <vs-td :data="data[indextr].description">
                    {{ data[indextr].description }}
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="visibility"
                          :to="{
                            name: 'RoleManagementRead',
                            params: { id: data[indextr].id },
                          }"
                        >
                          Detail
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="list"
                          :to="{
                            name: 'RoleManagementPermissions',
                            params: { id: data[indextr].id },
                          }"
                        >
                          Permission
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="edit"
                          :to="{
                            name: 'RoleManagementEdit',
                            params: { id: data[indextr].id },
                          }"
                          v-if="$helper.isAllowed('edit_roles')"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="confirmDelete(data[indextr].id)"
                          v-if="$helper.isAllowed('delete_roles')"
                        >
                          Delete
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </badaso-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("role.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "RoleManagementBrowse",
  components: {
  },
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    roles: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getRoleList();
  },
  methods: {
    confirmDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteRole,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.bulkDeleteRole,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    getRoleList() {
      this.$openLoader()
      this.$api.badasoRole
        .browse()
        .then((response) => {
          this.$closeLoader()
          this.selected = [];
          this.roles = response.data.roles;
        })
        .catch((error) => {
          this.$closeLoader()
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteRole() {
      this.$openLoader()
      this.$api.badasoRole
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$closeLoader()
          this.getRoleList();
        })
        .catch((error) => {
          this.$closeLoader()
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    bulkDeleteRole() {
      const ids = this.selected.map((item) => item.id);
      this.$openLoader()
      this.$api.badasoRole
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$closeLoader()
          this.getRoleList();
        })
        .catch((error) => {
          this.$closeLoader()
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
