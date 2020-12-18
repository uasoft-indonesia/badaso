<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" :to="{name: 'PermissionAdd'}"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
          <vs-button color="danger" type="relief" v-if="selected.length > 0"
            @click.stop
            @click="confirmDeleteMultiple"
            ><vs-icon icon="delete_sweep"></vs-icon> Bulk Delete</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <!-- <div slot="header">
            <h3>Browse</h3>
          </div> -->
          <div>
            <vs-table
              multiple
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="permissions"
              stripe
              description
              :description-items="descriptionItems"
              description-title="Registries"
              description-connector="of"
              description-body="Pages"
            >
              <template slot="header">
                <h3>Permissions</h3>
              </template>
              <template slot="thead">
                <vs-th sort-key="key"> Key </vs-th>
                <vs-th sort-key="description"> Description </vs-th>
                <vs-th sort-key="tableName"> Table Name </vs-th>
                <vs-th sort-key="alwaysAllow"> Alway Allow </vs-th>
                <vs-th> Action </vs-th>
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

                  <vs-td :data="data[indextr].alwaysAllow">
                    <span v-if="data[indextr].alwaysAllow === 1">Yes</span>
                    <span v-else>No</span>
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <vs-button color="success" type="relief" @click.stop
                    :to="{name: 'PermissionRead', params: {id: data[indextr].id}}"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button color="warning" type="relief"
                      @click.stop
                      :to="{name: 'PermissionEdit', params: {id: data[indextr].id}}"
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      @click="confirmDelete(data[indextr].id)"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
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
        }
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.bulkDeletePermission,
        cancel: () => {
        }
      });
    },
    getPermissionList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.permission
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.selected = []
          this.permissions = response.data;
        })
        .catch((error) => {
          console.log(error)
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    deletePermission() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.permission
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getPermissionList();
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
    bulkDeletePermission() {
      const ids = this.selected.map((item) => item.id)
      this.$vs.loading({
        type: "sound",
      });
      this.$api.permission
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getPermissionList();
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