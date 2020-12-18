<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" :to="{ name: 'RoleAdd' }"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
          <vs-button
            color="danger"
            type="relief"
            v-if="selected.length > 0"
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
              :data="roles"
              stripe
              description
              :description-items="descriptionItems"
              description-title="Registries"
              description-connector="of"
              description-body="Pages"
            >
              <template slot="header">
                <h3>Role</h3>
              </template>
              <template slot="thead">
                <vs-th sort-key="name"> Name </vs-th>
                <vs-th sort-key="displayName"> Display Name </vs-th>
                <vs-th sort-key="description"> Description </vs-th>
                <vs-th> Action </vs-th>
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
                    <vs-button
                      color="success"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'RoleRead',
                        params: { id: data[indextr].id },
                      }"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="warning"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'RoleEdit',
                        params: { id: data[indextr].id },
                      }"
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
    BadasoBreadcrumb,
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
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteRole,
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.bulkDeleteRole,
        cancel: () => {},
      });
    },
    getRoleList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.selected = []
          this.roles = response.data;
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
    deleteRole() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getRoleList();
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
    bulkDeleteRole() {
      const ids = this.selected.map((item) => item.id);
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getRoleList();
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
