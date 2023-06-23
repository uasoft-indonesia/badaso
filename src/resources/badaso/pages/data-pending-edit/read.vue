<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action"> </template>
    </badaso-breadcrumb-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("offlineFeature.dataPendingEdit.title") }}</h3>
          </div>
          <vs-row>
            <vs-col>
              <table class="badaso-table">
                <tr
                  v-for="(item, index) in itemDataStore"
                  :data="item"
                  :key="index"
                >
                  <td class="badaso-table__label">
                    {{ $helper.generateDisplayName(item.field) }}
                  </td>
                  <td class="badaso-table__value">{{ item.value }}</td>
                </tr>
              </table>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import { readObjectStore } from "../../utils/indexed-db";
export default {
  name: "DataPendingEditRead",
  components: {},
  data() {
    return {
      keyStore: null,
      itemDataStore: {},
    };
  },
  created() {
    this.keyStore = atob(this.$route.params.urlBase64);
  },
  mounted() {
    this.loadData();
  },
  methods: {
    loadData() {
      readObjectStore(this.keyStore)
        .then((store) => {
          if (store.result) {
            let data = store.result.data.filter((item, index) => {
              const { requestData } = item;
              return requestData.slug != undefined;
            });

            if (data.length == 0) return;

            data = data[0];
            if (data) {
              data = data.requestData.data;
              this.itemDataStore = data;
            }
          }
        })
        .catch((error) => console.log(error));
    },
  },
  computed: {
    isOnline: {
      get() {
        const isOnline = this.$store.getters["badaso/getGlobalState"].isOnline;
        return isOnline;
      },
    },
  },
};
</script>
