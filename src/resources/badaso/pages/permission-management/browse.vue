<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'PermissionManagementAdd' }"
          v-if="$helper.isAllowed('add_permissions')"
          ><vs-icon icon="add"></vs-icon> {{ $t("action.add") }}</vs-button
        >
        <vs-button
          color="danger"
          type="relief"
          v-if="selected.length > 0 && $helper.isAllowed('delete_permissions')"
          @click.stop
          @click="confirmDeleteMultiple"
          ><vs-icon icon="delete_sweep"></vs-icon>
          {{ $t("action.bulkDelete") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_permissions')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("permission.title") }}</h3>
          </div>
          <div>
            <badaso-table
              multiple
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="permissions"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('permission.footer.descriptionTitle')"
              :description-connector="
                $t('permission.footer.descriptionConnector')
              "
              :description-body="$t('permission.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="key">
                  {{ $t("permission.header.key") }}
                </vs-th>
                <vs-th sort-key="description">
                  {{ $t("permission.header.description") }}
                </vs-th>
                <vs-th sort-key="tableName">
                  {{ $t("permission.header.tableName") }}
                </vs-th>
                <vs-th sort-key="rolesCanSeeAllData">
                  {{ $t("permission.header.rolesCanSeeAllData") }}
                </vs-th>
                <vs-th sort-key="fieldIdentifyRelatedUser">
                  {{ $t("permission.header.fieldIdentifyRelatedUser") }}
                </vs-th>
                <vs-th sort-key="alwaysAllow">
                  {{ $t("permission.header.alwaysAllow") }}
                </vs-th>
                <vs-th sort-key="isPublic">
                  {{ $t("permission.header.isPublic") }}
                </vs-th>
                <vs-th> {{ $t("permission.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].key">
                    {{ data[indextr].key }}
                  </vs-td>

                  <vs-td :data="data[indextr].description">
                    {{ data[indextr].description }}
                  </vs-td>

                  <vs-td :data="data[indextr].tableName">
                    {{ data[indextr].tableName }}
                  </vs-td>

                  <vs-td :data="data[indextr].rolesCanSeeAllData">
                    {{ data[indextr].rolesCanSeeAllData }}
                  </vs-td>

                  <vs-td :data="data[indextr].fieldIdentifyRelatedUser">
                    {{ data[indextr].fieldIdentifyRelatedUser }}
                  </vs-td>

                  <vs-td :data="data[indextr].alwaysAllow">
                    <span v-if="data[indextr].alwaysAllow == 1">Yes</span>
                    <span v-else>No</span>
                  </vs-td>

                  <vs-td :data="data[indextr].isPublic">
                    <span v-if="data[indextr].isPublic == 1">Yes</span>
                    <span v-else>No</span>
                  </vs-td>
                  <vs-td class="badaso-table__td">
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
                            name: 'PermissionManagementRead',
                            params: { id: data[indextr].id },
                          }"
                        >
                          Detail
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="edit"
                          :to="{
                            name: 'PermissionManagementEdit',
                            params: { id: data[indextr].id },
                          }"
                          v-if="$helper.isAllowed('edit_permissions')"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="confirmDelete(data[indextr].id)"
                          v-if="$helper.isAllowed('delete_permissions')"
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
              <h3>{{ $t("permission.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "PermissionManagementBrowse",
  components: {},
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    permissions: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getPermissionList();
  },
  methods: {
    confirmDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deletePermission,
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
        accept: this.bulkDeletePermission,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    getPermissionList() {
      this.$openLoader();
      this.$api.badasoPermission
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.selected = [];
          this.permissions = response.data.permissions;
          this.permissions.map((value) => {
            if (value.rolesCanSeeAllData) {
              const rolesAllData = JSON.parse(value.rolesCanSeeAllData);
              value.rolesCanSeeAllData = rolesAllData.toString();
            }
            return value;
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
    deletePermission() {
      this.$openLoader();
      this.$api.badasoPermission
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$closeLoader();
          this.getPermissionList();
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
    bulkDeletePermission() {
      const ids = this.selected.map((item) => item.id);
      this.$openLoader();
      this.$api.badasoPermission
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$closeLoader();
          this.getPermissionList();
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
