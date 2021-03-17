<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" :to="{ name: 'DatabaseAdd' }"
            v-if="$helper.isAllowed('add_database')"
            ><vs-icon icon="add"></vs-icon> {{ $t('database.browse.addButton') }}</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('browse_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('database.browse.title') }}</h3>
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
              :description-title="$t('database.add.footer.descriptionTitle')"
              :description-connector="$t('database.add.footer.descriptionConnector')"
              :description-body="$t('database.add.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="tableName"> {{ $t('crud.header.table') }} </vs-th>
                <vs-th> {{ $t('crud.header.action') }} </vs-th>
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
                    v-if="data[index].crudData"
                  >
                    <vs-button
                      color="success"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'EntityBrowse',
                        params: { slug: data[index].crudData.slug },
                      }"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="warning"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('edit_crud_data')"
                      :to="{
                        name: 'CRUDManagementEdit',
                        params: { tableName: data[index].tableName },
                      }"
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('delete_crud_data')"
                      @click="openConfirm(data[index].crudData.id)"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                  <vs-td v-else style="width: 1%; white-space: nowrap">
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('edit_database')"
                      :to="{
                        name: 'DatabaseAlter',
                        params: { tableName: data[index].tableName },
                      }"
                      >{{ $t('database.browse.alterButton') }}</vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('delete_database')"
                      :to="{
                        name: 'CRUDManagementAdd',
                        params: { tableName: data[index].tableName },
                      }"
                      >{{ $t('database.browse.dropButton') }}</vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t('crud.warning.notAllowed') }}</h3>
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
  components: { BadasoBreadcrumb },
  name: "Browse",
  data: () => ({
    descriptionItems: [10, 50, 100],
    selected: [],
    tables: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getTableList();
  },
  methods: {
    openConfirm(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t('action.delete.title'),
        text: this.$t('action.delete.text'),
        accept: this.deleteCRUDData,
        acceptText: this.$t('action.delete.accept'),
        cancelText: this.$t('action.delete.cancel'),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    getTableList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.crud
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.tables = response.data.tablesWithCrudData;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteCRUDData() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.crud
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getTableList();
          this.$store.commit("FETCH_MENU");
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
