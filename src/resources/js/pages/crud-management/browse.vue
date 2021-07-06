<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_crud_data')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("crud.title") }}</h3>
          </div>
          <div>
            <badaso-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="tables"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('crud.footer.descriptionTitle')"
              :description-connector="$t('crud.footer.descriptionConnector')"
              :description-body="$t('crud.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="tableName">
                  {{ $t("crud.header.table") }}
                </vs-th>
                <vs-th> {{ $t("crud.header.action") }} </vs-th>
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
                    class="badaso-table__td"
                    v-if="data[index].crudData"
                  >
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
                            name: 'CrudGeneratedBrowse',
                            params: { slug: data[index].crudData.slug },
                          }"
                        >
                          Detail
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="edit"
                          v-if="$helper.isAllowed('edit_crud_data')"
                          :to="{
                            name: 'CrudManagementEdit',
                            params: { tableName: data[index].tableName },
                          }"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          v-if="$helper.isAllowed('delete_crud_data')"
                          @click="openConfirm(data[index].crudData.id)"
                        >
                          Delete
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                  <vs-td v-else class="badaso-table__td">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="add"
                          v-if="$helper.isAllowed('add_crud_data')"
                          :to="{
                            name: 'CrudManagementAdd',
                            params: { tableName: data[index].tableName },
                          }"
                        >
                          {{ $t("crud.body.button") }}
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
              <h3>{{ $t("crud.warning.notAllowed") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  components: {},
  name: "CrudManagementBrowse",
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
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteCRUDData,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    getTableList() {
      this.$openLoader();
      this.$api.badasoCrud
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.tables = response.data.tablesWithCrudData;
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
    deleteCRUDData() {
      this.$openLoader();
      this.$api.badasoCrud
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$closeLoader();
          this.getTableList();
          this.$store.commit("badaso/FETCH_MENU");
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
