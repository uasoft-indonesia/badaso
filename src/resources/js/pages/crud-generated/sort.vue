<template>
  <div>
    <template v-if="!isMaintenance">
      <badaso-breadcrumb-row full> </badaso-breadcrumb-row>
      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('edit', dataType)">
        <vs-col vs-lg="12">
          <vs-card>
            <div slot="header">
              <h3>
                {{
                  $t("crudGenerated.sort.title", {
                    tableName: dataType.displayNameSingular,
                  })
                }}
              </h3>
            </div>
            <vs-row>
              <vs-col vs-lg="12">
                <table class="badaso-table badaso-table__striped">
                  <draggable v-model="data" tag="tbody" @change="sortData">
                    <tr :key="index" v-for="(field, index) in data">
                      <td class="crud-generated__draggable">
                        <vs-icon
                          icon="drag_indicator"
                          class="is-draggable"
                        ></vs-icon>
                      </td>
                      <td
                        :data="
                          field[
                            $caseConvert.stringSnakeToCamel(
                              dataType.orderColumn
                            )
                          ]
                        "
                      >
                        <strong>{{
                          field[
                            $caseConvert.stringSnakeToCamel(
                              dataType.orderDisplayColumn
                            )
                          ]
                        }}</strong>
                      </td>
                    </tr>
                  </draggable>
                </table>
              </vs-col>
            </vs-row>
          </vs-card>
        </vs-col>
      </vs-row>
      <vs-row v-else>
        <vs-col vs-lg="12">
          <vs-card>
            <vs-row>
              <vs-col vs-lg="12">
                <h3>
                  {{
                    $t("crudGenerated.warning.notAllowedToRead", {
                      tableName: dataType.displayNameSingular,
                    })
                  }}
                </h3>
              </vs-col>
            </vs-row>
          </vs-card>
        </vs-col>
      </vs-row>
    </template>
    <template v-if="isMaintenance">
      <badaso-breadcrumb-row full> </badaso-breadcrumb-row>

      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('browse', dataType)">
        <vs-col vs-lg="12">
          <div class="badaso-maintenance__container">
            <img :src="`${maintenanceImg}`" alt="Maintenance Icon" />
            <h1 class="badaso-maintenance__text">
              We are under <br />maintenance
            </h1>
          </div>
        </vs-col>
      </vs-row>
    </template>
  </div>
</template>

<script>
// eslint-disable-next-line no-unused-vars
import _ from "lodash";
import draggable from "vuedraggable";

export default {
  name: "CrudGeneratedSort",
  components: {
    draggable,
  },
  data: () => ({
    dataType: {},
    record: {},
    data: [],
    isMaintenance: false,
  }),
  mounted() {
    this.getAllEntityData();
  },
  computed: {
    maintenanceImg() {
      const config = this.$store.getters["badaso/getConfig"];
      return config.maintenanceImage;
    },
  },
  methods: {
    async getAllEntityData() {
      this.$openLoader();

      try {
        const response = await this.$api.badasoEntity.all({
          slug: this.$route.params.slug,
        });

        const {
          data: { dataType },
        } = await this.$api.badasoTable.getDataType({
          slug: this.$route.params.slug,
        });

        this.$closeLoader();

        this.dataType = dataType;
        this.data = response.data.data;
      } catch (error) {
        if (error.status == 503) {
          this.isMaintenance = true;
        }
        this.$closeLoader();
        this.$vs.notify({
          title: this.$t("alert.danger"),
          text: error.message,
          color: "danger",
        });
      }
    },
    sortData(e) {
      this.$openLoader();
      this.$api.badasoEntity
        .sort({
          slug: this.$route.params.slug,
          data: this.data,
        })
        .then((response) => {
          this.$closeLoader();
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
