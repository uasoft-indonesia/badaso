<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action" />
    </badaso-breadcrumb-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("offlineFeature.dataPendingAdd.title") }}</h3>
          </div>
          <div>
            <badaso-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="dataDBObject"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('crud.footer.descriptionTitle')"
              :description-connector="$t('crud.footer.descriptionConnector')"
              :description-body="$t('crud.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th
                  v-for="(header, index) in headerDataDBObject"
                  :key="index"
                  :data="header"
                  sort-key="tableName"
                >
                  {{ $helper.generateDisplayName(header.field) }}
                </vs-th>
                <vs-th> {{ $t("crud.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  v-for="(dataRows, indeDataRows) in data"
                  :key="indeDataRows"
                  :data="dataRows"
                >
                  <vs-td
                    v-for="(dataColumn, indexColumn) in dataRows.requestData
                      .data"
                    :key="indexColumn"
                    :data="dataColumn"
                  >
                    {{ dataColumn.value }}
                  </vs-td>
                  <vs-td class="badaso-table__td">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button size="large" type="flat" icon="more_vert" />
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          v-if="$helper.isAllowed('delete_menus')"
                          icon="delete"
                          @click="openConfirm(indeDataRows)"
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
  </div>
</template>
<script>
import { readObjectStore, setObjectStore } from "../../utils/indexed-db";
export default {
  name: "DataPendingAddBrowse",
  components: {},
  data() {
    return {
      descriptionItems: [10, 50, 100],
      dataDBObject: [],
      keyStore: null,
      selected: [],
      headerDataDBObject: {},
    };
  },
  computed: {},
  created() {
    this.keyStore = atob(this.$route.params.urlBase64);
  },
  mounted() {
    this.dbReadDataObject();
  },
  methods: {
    dbReadDataObject() {
      readObjectStore(this.keyStore)
        .then((store) => {
          const data = store.result.data.filter((item, index) => {
            const { requestData } = item;
            return requestData.slug != undefined;
          });

          if (data.length == 0) return;

          this.dataDBObject = data;
          this.headerDataDBObject = this.dataDBObject[0].requestData.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    openConfirm(indexItem) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: () => this.deleteDataPending(indexItem),
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
      });
    },
    deleteDataPending(indexItem) {
      readObjectStore(this.keyStore)
        .then((store) => {
          const dataObject = store.result.data;
          const newDataObject = [];
          for (const index in dataObject) {
            if (indexItem != index) {
              newDataObject[newDataObject.length] = dataObject[index];
            }
          }
          setObjectStore(this.keyStore, { data: newDataObject });
          this.dataDBObject = newDataObject;
          if (this.dataDBObject.length == 0) this.$router.go(-1);
        })
        .catch((error) => {
          console.error(error);
        });
    },
  },
};
</script>
