<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb full></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" :to="{name: 'EntityAdd'}"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
          <vs-button color="danger" type="relief" v-if="selected.length > 0"
            @click.stop
            @click="confirmDeleteMultiple"
            ><vs-icon icon="delete_sweep"></vs-icon> Bulk Delete</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ dataType.displayNameSingular }}</h3>
          </div>
          <div>
            <vs-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="records"
              stripe
              description
              :description-items="descriptionItems"
              description-title="Registries"
              description-connector="of"
              description-body="Pages"
              multiple
            >
              <template slot="thead">
                <vs-th
                  v-for="(dataRow, index) in dataType.dataRows"
                  v-if="dataRow.browse === 1"
                  :key="index"
                  :sort-key="dataRow.field"
                >
                  {{ dataRow.displayName }}
                </vs-th>
                <vs-th> Action </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  :data="record"
                  :key="index"
                  v-for="(record, index) in data"
                >
                  <vs-td
                    v-for="(dataRow, indexColumn) in dataType.dataRows"
                    v-if="dataRow.browse === 1"
                    :key="indexColumn"
                    :data="
                      data[index][
                        $caseConvert.stringSnakeToCamel(dataRow.field)
                      ]
                    "
                  >
                    <img
                      v-if="dataRow.type === 'upload_image'"
                      :src="
                        `/badaso-api/v1/file/view?file=${
                          record[$caseConvert.stringSnakeToCamel(dataRow.field)]
                        }`
                      "
                      width="100%"
                      alt=""
                    />
                    <div v-else-if="dataRow.type === 'upload_image_multiple'" style="width: 100%;">
                      <img
                        v-for="(image, indexImage) in stringToArray(record[$caseConvert.stringSnakeToCamel(dataRow.field)])"
                        :key="indexImage"
                        :src="
                          `/badaso-api/v1/file/view?file=${
                            image
                          }`
                        "
                        width="100%"
                        alt=""
                        style="margin-bottom: 10px;"
                      />
                    </div>
                    <span v-else-if="dataRow.type === 'editor'" v-html="record[$caseConvert.stringSnakeToCamel(dataRow.field)]"></span>
                    <a v-else-if="dataRow.type === 'url'" :href="record[$caseConvert.stringSnakeToCamel(dataRow.field)]" target="_blank">{{record[$caseConvert.stringSnakeToCamel(dataRow.field)]}}</a>
                    <a v-else-if="dataRow.type === 'upload_file'" :href="`/badaso-api/v1/file/download?file=${record[$caseConvert.stringSnakeToCamel(dataRow.field)]}`" target="_blank">{{record[$caseConvert.stringSnakeToCamel(dataRow.field)]}}</a>
                    <div v-else-if="dataRow.type === 'upload_file_multiple'" style="width: 100%;">
                      <p v-for="(file, indexFile) in stringToArray(record[$caseConvert.stringSnakeToCamel(dataRow.field)])" :key="indexFile">
                      <a :href="`/badaso-api/v1/file/download?file=${file}`" target="_blank">{{file}}</a>
                      </p>
                    </div>
                    <p v-else-if="dataRow.type === 'radio' || dataRow.type === 'select'">{{bindSelection(dataRow.details.items, record[$caseConvert.stringSnakeToCamel(dataRow.field)])}}</p>
                    <div v-else-if="dataRow.type === 'select_multiple' || dataRow.type === 'checkbox'" style="width: 100%">
                      <p v-for="(selected, indexSelected) in stringToArray(record[$caseConvert.stringSnakeToCamel(dataRow.field)])" :key="indexSelected">{{bindSelection(dataRow.details.items, selected)}}</p>
                    </div>
                    <div v-else-if="dataRow.type === 'color_picker'">
                      <div :style="`width: 100%; height: 14px; background-color: ${record[$caseConvert.stringSnakeToCamel(dataRow.field)]}`"></div>
                      {{record[$caseConvert.stringSnakeToCamel(dataRow.field)]}}
                    </div>
                    <span v-else>{{ record[$caseConvert.stringSnakeToCamel(dataRow.field)] }}</span>
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <vs-button
                      color="success"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'EntityRead',
                        params: { id: data[index].id, slug: $route.params.slug },
                      }"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="warning"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'EntityEdit',
                        params: { id: data[index].id, slug: $route.params.slug },
                      }"
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      @click="confirmDelete(data[index].id)"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
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
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";
export default {
  components: { BadasoBreadcrumb },
  name: "Browse",
  data: () => ({
    descriptionItems: [10, 50, 100],
    selected: [],
    records: [],
    dataType: [],
    willDeleteId: null,
  }),
  watch: {
    $route: function(to, from) {
      this.getEntity();
    },
  },
  mounted() {
    this.getEntity();
  },
  methods: {
    confirmDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteRecord,
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteRecords,
        cancel: () => {
        }
      });
    },
    getEntity() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .browse({
          slug: this.$route.params.slug
        })
        .then((response) => {
          this.$vs.loading.close();
          this.records = response.data.list;
          this.dataType = response.data.dataType;
          let dataRows = this.dataType.dataRows.map((data) => {
              try {
                data.details = JSON.parse(data.details)
              } catch (error) {}
              return data
          });
          this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows))
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
    deleteRecord() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .delete({
          slug: this.$route.params.slug,
          data: [
            {
              field: 'id',
              value: this.willDeleteId
            }
          ],
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getEntity();
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
    deleteRecords() {
      const ids = this.selected.map((item) => item.id)
      this.$vs.loading({
        type: "sound",
      });
      this.$api.entity
        .deleteMultiple({
          slug: this.$route.params.slug, 
          data: [
            {
              field: 'ids',
              value: ids.join(",")
            }
          ],
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getEntity();
        })
        .catch((error) => {
          console.log(error)
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    bindSelection(items, value) {
      const selected = _.find(items, ['value', value]);
      if (selected) {
        return selected.label;
      } else {
        return value
      }
    },
    stringToArray(str) {
      if (str) {
        return str.split(',')
      } else {
        return []
      }
    }
  },
};
</script>
