<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <vs-breadcrumb
          style="margin-bottom: 0"
          :items="breadcrumb"
        ></vs-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" to="add"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
          <vs-button color="danger" type="relief"
            ><vs-icon icon="delete_sweep"></vs-icon> Bulk Delete</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Bread</h3>
          </div>
          <div>
            <vs-table
              multiple
              v-model="selected"
              pagination
              max-items="3"
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
                <vs-th sort-key="table_name"> Table </vs-th>
                <vs-th> Action </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  :data="table"
                  :key="index"
                  v-for="(table, index) in data"
                >
                  <vs-td :data="data[index].table_name">
                    {{ data[index].table_name }}
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap" v-if="data[index].bread">
                    <vs-button color="success" type="relief" @click.stop
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button color="warning" type="relief" @click.stop
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      @click="openConfirm()"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                  <vs-td v-else style="width: 1%; white-space: nowrap">
                    <vs-button color="rgb(187, 138, 200)" type="relief" @click.stop
                      >Add BREAD to this table</vs-button>
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
export default {
  name: "Browse",
  data: () => ({
    descriptionItems: [3, 5, 15],
    breadcrumb: [
      {
        title: "Dashboard",
        url: "dashboard",
      },
      {
        title: "Bread",
        active: true,
      },
    ],
    selected: [],
    tables: [],
  }),
  mounted() {
    this.getTableList();
  },
  methods: {
    openConfirm() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text:
          "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
        accept: this.acceptAlert,
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
          this.tables = response.data_list;
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
