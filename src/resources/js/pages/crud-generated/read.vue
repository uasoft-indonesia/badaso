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
              >
                <template v-if="dataRow.read">
                  <table class="badaso-table">
                    <tr>
                      <td class="badaso-table__label">
                        {{ dataRow.displayName }}
                      </td>
                      <td class="badaso-table__value">
                        <img
                          v-if="dataRow.type == 'upload_image'"
                          :src="
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          "
                          width="100%"
                          alt=""
                        />
                        <div
                          v-else-if="dataRow.type == 'upload_image_multiple'"
                          class="crud-generated__item--upload-image-multiple"
                        >
                          <img
                            v-for="(image, indexImage) in stringToArray(
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            )"
                            :key="indexImage"
                            :src="image"
                            width="100%"
                            alt=""
                            class="crud-generated__item--image"
                          />
                        </div>
                        <span
                          v-else-if="dataRow.type == 'editor'"
                          v-html="
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          "
                        ></span>
                        <a
                          v-else-if="dataRow.type == 'url'"
                          :href="
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          "
                          target="_blank"
                          >{{
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          }}</a
                        >
                        <a
                          v-else-if="dataRow.type == 'upload_file'"
                          :href="`${
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          }`"
                          target="_blank"
                          >{{
                            getDownloadUrl(
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            )
                          }}</a
                        >
                        <div
                          v-else-if="dataRow.type == 'upload_file_multiple'"
                          class="crud-generated__item--upload-file-multiple"
                        >
                          <p
                            v-for="(file, indexFile) in arrayToString(
                              dataRow.value
                            )"
                            :key="indexFile"
                          >
                            <a :href="`${file}`" target="_blank">{{
                              getDownloadUrl(file)
                            }}</a>
                          </p>
                        </div>
                        <p
                          v-else-if="
                            dataRow.type == 'radio' || dataRow.type == 'select'
                          "
                        >
                          {{
                            bindSelection(
                              dataRow.details.items,
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            )
                          }}
                        </p>
                        <div
                          v-else-if="
                            dataRow.type == 'select_multiple' ||
                            dataRow.type == 'checkbox'
                          "
                          class="crud-generated__item--select-multiple"
                        >
                          <p
                            v-for="(selected, indexSelected) in stringToArray(
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            )"
                            :key="indexSelected"
                          >
                            {{ bindSelection(dataRow.details.items, selected) }}
                          </p>
                        </div>
                        <div v-else-if="dataRow.type == 'color_picker'">
                          <div
                            class="crud-generated__item--color-picker"
                            :style="`background-color: ${
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            }`"
                          ></div>
                          {{
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          }}
                        </div>
                        <span v-else-if="dataRow.type == 'relation'">{{
                          displayRelationData(record, dataRow)
                        }}</span>
                        <span v-else>{{
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        }}</span>
                      </td>
                    </tr>
                  </table>
                </template>
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
import _ from "lodash";

export default {
  name: "CrudGeneratedRead",
  components: {},
  data: () => ({
    dataType: {},
    record: {},
    isMaintenance: false,
  }),
  mounted() {
    this.getDetailEntity();
  },
  computed: {
    maintenanceImg() {
      const config = this.$store.getters["badaso/getConfig"];
      return config.maintenanceImage;
    },
  },
  methods: {
    getDownloadUrl(item) {
      if (item == null || item == undefined) return;

      return item.split("storage").pop();
    },
    async getDetailEntity() {
      this.$openLoader();

      try {
        const response = await this.$api.badasoEntity.read({
          slug: this.$route.params.slug,
          id: this.$route.params.id,
        });

        const {
          data: { dataType },
        } = await this.$api.badasoTable.getDataType({
          slug: this.$route.params.slug,
        });

        this.$closeLoader();
        this.dataType = dataType;
        this.record = response.data;

        const dataRows = this.dataType.dataRows.map((data) => {
          try {
            data.add = data.add == 1;
            data.edit = data.edit == 1;
            data.read = data.read == 1;
            data.details = JSON.parse(data.details);
            if (data.type == "upload_file_multiple") {
              const val =
                this.record[this.$caseConvert.stringSnakeToCamel(data.field)];

              if (val) {
                data.value = val.toString();
              }
            }
          } catch (error) {}
          return data;
        });

        this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows));
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
   arrayToString(files) {
      if (files) {
        const mixArray = files;
        const str = mixArray.replace(/\[|\]|"/g, "").split(",");
        return str;
      } else {
        return [];
      }
    },
    displayRelationData(record, dataRow) {
      const table = this.$caseConvert.stringSnakeToCamel(
        dataRow.relation.destinationTable
      );
      this.$caseConvert.stringSnakeToCamel(
        dataRow.relation.destinationTableColumn
      );
      const displayColumn = this.$caseConvert.stringSnakeToCamel(
        dataRow.relation.destinationTableDisplayColumn
      );

      if (dataRow.relation.relationType == "has_one") {
        const list = record[table];
        return list[displayColumn];
      } else if(dataRow.relation.relationType == "has_many") {
        const list = record[table];
        const flatList = list.map((ls) => {
          return ls[displayColumn];
        });
        return flatList.join(", ");
      } else if (dataRow.relation.relationType == "belongs_to") {
        const lists = record[table];
        let field = this.$caseConvert.stringSnakeToCamel(dataRow.field);
        for (let list of lists) {
          if (list.id == record[field]) {
            return list[displayColumn];
          }
        }
      } else if (dataRow.relation.relationType == "belongs_to_many") {
        let field = this.$caseConvert.stringSnakeToCamel(dataRow.field);
        const lists = record[field];
        let flatList = [];
        Object.keys(lists).forEach(function (ls, key) {
          flatList.push(lists[ls][displayColumn]);
        });

        return flatList.join(",").replace(",", ", ");
      } else {
        return record[table] ? record[table][displayColumn] : null;
      }
    },
  },
};
</script>
