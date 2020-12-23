<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb full></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Detail</h3>
          </div>
          <vs-row>
            <vs-col
              v-for="(dataRow, rowIndex) in dataType.dataRows"
              :key="rowIndex"
              :vs-lg="dataRow.details.size ? dataRow.details.size : '12'"
              v-if="dataRow.add === 1"
            >
                <table class="table">
                    <tr>
                        <td class="display-label">{{dataRow.displayName}}</td>
                        <td class="display-value">
                            <img v-if="dataRow.type === 'upload_image'" :src="`/badaso-api/v1/file/view?file=${record[$caseConvert.stringSnakeToCamel(dataRow.field)]}`" width="100%" alt="">
                            <span v-else>{{record[$caseConvert.stringSnakeToCamel(dataRow.field)]}}</span>
                        </td>
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
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";

export default {
  name: "Read",
  components: {
    BadasoBreadcrumb,
  },
  data: () => ({
    dataType: {},
    record: {},
  }),
  mounted() {
    this.getDetailEntity();
  },
  methods: {
    getDetailEntity() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .read({
          slug: this.$route.params.slug,
          id: this.$route.params.id
        })
        .then((response) => {
          this.$vs.loading.close();
          this.dataType = response.data.dataType;
          this.record = response.data.detail;
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

<style>
.display-label {
    width: 30%;
    font-weight: bold;
}
.display-value {
    widows: 70%;
}
</style>