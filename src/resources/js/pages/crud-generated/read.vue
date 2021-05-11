<template>
  <div>
    <template v-if="!isMaintenance">
      <badaso-breadcrumb-row full>
        <template slot="action">
          <vs-button
            color="warning"
            type="relief"
            v-if="$helper.isAllowedToModifyGeneratedCRUD('edit', dataType)"
            :to="{
              name: 'CrudGeneratedEdit',
              params: { id: $route.params.id, slug: $route.params.slug },
            }"
            ><vs-icon icon="edit"></vs-icon>
            {{ $t("crudGenerated.detail.button") }}</vs-button
          >
        </template>
      </badaso-breadcrumb-row>
      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('read', dataType)">
        <vs-col vs-lg="12">
          <vs-card>
            <div slot="header">
              <h3>
                {{
                  $t("crudGenerated.detail.title", {
                    tableName: dataType.displayNameSingular,
                  })
                }}
              </h3>
            </div>
            <vs-row>
              <vs-col
                v-for="(dataRow, rowIndex) in dataType.dataRows"
                :key="rowIndex"
                :vs-lg="dataRow.details.size ? dataRow.details.size : '12'"
                v-if="dataRow.read"
              >
                <table class="table">
                  <tr>
                    <td class="display-label">{{ dataRow.displayName }}</td>
                    <td class="display-value">
                      <img
                        v-if="dataRow.type === 'upload_image'"
                        :src="
                          `${$api.badasoFile.view(
                            record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                          )}`
                        "
                        width="100%"
                        alt=""
                      />
                      <div
                        v-else-if="dataRow.type === 'upload_image_multiple'"
                        style="width: 100%;"
                      >
                        <img
                          v-for="(image, indexImage) in stringToArray(
                            record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                          )"
                          :key="indexImage"
                          :src="`${$api.badasoFile.view(image)}`"
                          width="100%"
                          alt=""
                          style="margin-bottom: 10px;"
                        />
                      </div>
                      <span
                        v-else-if="dataRow.type === 'editor'"
                        v-html="
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        "
                      ></span>
                      <a
                        v-else-if="dataRow.type === 'url'"
                        :href="
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        "
                        target="_blank"
                        >{{
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        }}</a
                      >
                      <a
                        v-else-if="dataRow.type === 'upload_file'"
                        :href="
                          `${$api.badasoFile.download(
                            record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                          )}`
                        "
                        target="_blank"
                        >{{
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        }}</a
                      >
                      <div
                        v-else-if="dataRow.type === 'upload_file_multiple'"
                        style="width: 100%;"
                      >
                        <p
                          v-for="(file, indexFile) in stringToArray(
                            record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                          )"
                          :key="indexFile"
                        >
                          <a
                            :href="`${$api.badasoFile.download(file)}`"
                            target="_blank"
                            >{{ file }}</a
                          >
                        </p>
                      </div>
                      <p
                        v-else-if="
                          dataRow.type === 'radio' || dataRow.type === 'select'
                        "
                      >
                        {{
                          bindSelection(
                            dataRow.details.items,
                            record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                          )
                        }}
                      </p>
                      <div
                        v-else-if="
                          dataRow.type === 'select_multiple' ||
                            dataRow.type === 'checkbox'
                        "
                        style="width: 100%"
                      >
                        <p
                          v-for="(selected, indexSelected) in stringToArray(
                            record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                          )"
                          :key="indexSelected"
                        >
                          {{ bindSelection(dataRow.details.items, selected) }}
                        </p>
                      </div>
                      <div v-else-if="dataRow.type === 'color_picker'">
                        <div
                          :style="
                            `width: 100%; height: 14px; background-color: ${
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            }`
                          "
                        ></div>
                        {{
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        }}
                      </div>
                      <span v-else-if="dataRow.type === 'relation'">{{
                        displayRelationData(record, dataRow)
                      }}</span>
                      <span v-else>{{
                        record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                      }}</span>
                    </td>
                  </tr>
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
      <badaso-breadcrumb-row full>
      </badaso-breadcrumb-row>

      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('browse', dataType)">
        <vs-col vs-lg="12">
          <div class="flex flex-direction-column justify-content-center align-items-center" >
            <img src="/badaso-images/maintenance.png" alt="Maintenance Icon">

            <h1 class="mt-4 text-center">We are under <br>maintenance</h1>
          </div>
        </vs-col>
      </vs-row>
    </template>
  </div>
</template>

<script>
import _ from "lodash";

export default {
  name: "CrudGeneratedRead",
  components: {},
  data: () => ({
    dataType: {},
    record: {},
    isMaintenance: false
  }),
  mounted() {
    this.getDetailEntity();
  },
  methods: {
    getDetailEntity() {
      this.$openLoader();
      this.$api.badasoEntity
        .read({
          slug: this.$route.params.slug,
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.dataType = response.data.dataType;
          this.record = response.data.entities;

          let dataRows = this.dataType.dataRows.map((data) => {
            try {
              data.add = data.add === 1;
              data.edit = data.edit === 1;
              data.read = data.read === 1;
              data.details = JSON.parse(data.details);
            } catch (error) {}
            return data;
          });
          this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows));
        })
        .catch((error) => {
          if (error.status === 503) {
            this.isMaintenance = true;
          }
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    bindSelection(items, value) {
      const selected = _.find(items, ["value", value]);
      if (selected) {
        return selected.label;
      } else {
        return value;
      }
    },
    stringToArray(str) {
      if (str) {
        return str.split(",");
      } else {
        return [];
      }
    },
    displayRelationData(record, dataRow) {
      let table = this.$caseConvert.stringSnakeToCamel(
        dataRow.relation.destinationTable
      );
      let column = this.$caseConvert.stringSnakeToCamel(
        dataRow.relation.destinationTableColumn
      );
      let displayColumn = this.$caseConvert.stringSnakeToCamel(
        dataRow.relation.destinationTableDisplayColumn
      );
      if (dataRow.relation.relationType === "has_many") {
        let list = record[table];
        let flatList = list.map((ls) => {
          return ls[displayColumn];
        });
        return flatList.join(", ");
      } else {
        return record[table] ? record[table][displayColumn] : null;
      }
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
<style lang="scss" scoped>
.flex {
  display: flex;
}

.justify-content-center {
  justify-content: center;
}

.flex-direction-column {
  flex-direction: column;
}

.align-items-center {
  align-items: center;
}
</style>
