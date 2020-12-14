<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4"> </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Bread</h3>
          </div>
          <div>
            <vs-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="tables"
              stripe
              description
              :description-items="descriptionItems"
              description-title="Registries"
              description-connector="of"
              description-body="Pages"
            >
              <template slot="thead">
                <vs-th sort-key="tableName"> Table </vs-th>
                <vs-th> Action </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  :data="table"
                  :key="index"
                  v-for="(table, index) in data"
                >
                  <vs-td :data="data[index].tableName">
                    {{ data[index].tableName }}
                  </vs-td>
                  <vs-td
                    style="width: 1%; white-space: nowrap"
                    v-if="data[index].breadData"
                  >
                    <vs-button
                      color="success"
                      type="relief"
                      @click.stop
                      :to="{name: 'EntityBrowse', params: {slug: data[index].breadData.slug}}"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="warning"
                      type="relief"
                      @click.stop
                      :to="{name: 'BreadEdit', params: {tableName: data[index].tableName}}"
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      @click="openConfirm(data[index].breadData.id)"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                  <vs-td v-else style="width: 1%; white-space: nowrap">
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.stop
                      :to="{name: 'BreadAdd', params: {tableName: data[index].tableName}}"
                      >Add BREAD to this table</vs-button
                    >
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
  components: { BadasoBreadcrumb },
  name: "Browse",
  data: () => ({
    descriptionItems: [10, 50, 100],
    selected: [],
    tables: [],
    willDeleteBreadId: null,
  }),
  mounted() {
    this.getTableList();
  },
  methods: {
    openConfirm(breadId) {
      this.willDeleteBreadId = breadId;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteBread,
        cancel: () => {
          this.willDeleteBreadId = null;
        },
        breadId: breadId,
      });
    },
    getTableList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.tables = response.records;
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
    deleteBread() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .delete({
          id: this.willDeleteBreadId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getTableList();
          this.$store.commit("FETCH_MENU");
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
