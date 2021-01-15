<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb full></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('edit', dataType)">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Sort</h3>
          </div>
          <vs-row>
            <vs-col vs-lg="12">
              <table class="table table-striped">
                <draggable v-model="data" tag="tbody" @change="sortData">
                  <tr :key="index" v-for="(field, index) in data">
                    <td style="width: 1%">
                      <vs-icon
                        icon="drag_indicator"
                        class="drag_icon"
                      ></vs-icon>
                    </td>
                    <td
                      :data="
                        field[
                          $caseConvert.stringSnakeToCamel(dataType.orderColumn)
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
                You're not allowed to read {{ dataType.displayNameSingular }}
              </h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import _ from "lodash";
import draggable from "vuedraggable";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";

export default {
  name: "Sort",
  components: {
    draggable,
    BadasoBreadcrumb,
  },
  data: () => ({
    dataType: {},
    record: {},
    data: [],
  }),
  mounted() {
    this.getAllEntityData();
  },
  methods: {
    getAllEntityData() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .all({
          slug: this.$route.params.slug,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.dataType = response.data.dataType;
          this.data = response.data.entities.data;
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
    sortData(e) {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .sort({
          slug: this.$route.params.slug,
          data: this.data,
        })
        .then((response) => {
          this.$vs.loading.close();
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
