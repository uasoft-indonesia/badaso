<template>
  <div>
    <badaso-breadcrumb-row full>
      <template slot="action">
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'CrudGeneratedAdd' }"
          v-if="
            isCanAdd && $helper.isAllowedToModifyGeneratedCRUD('add', dataType)
          "
          ><vs-icon icon="add"></vs-icon> {{ $t("action.add") }}</vs-button
        >
        <vs-button
          color="success"
          type="relief"
          :to="{ name: 'CrudGeneratedSort' }"
          v-if="
            isCanSort &&
              $helper.isAllowedToModifyGeneratedCRUD('edit', dataType)
          "
          ><vs-icon icon="list"></vs-icon> {{ $t("action.sort") }}</vs-button
        >
        <vs-button
          color="danger"
          type="relief"
          v-if="
            selected.length > 0 &&
              $helper.isAllowedToModifyGeneratedCRUD('delete', dataType)
          "
          @click.stop
          @click="confirmDeleteMultiple"
          ><vs-icon icon="delete_sweep"></vs-icon>
          {{ $t("action.bulkDelete") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>

    <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('browse', dataType)">
      <vs-col vs-lg="12">
        <vs-alert
          :active="Object.keys(errors).length > 0"
          color="danger"
          icon="new_releases"
          style="margin-bottom: 20px;"
        >
          <span v-for="key in Object.keys(errors)">
            <span v-for="err in errors[key]">
              {{ err }}
            </span>
          </span>
        </vs-alert>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ dataType.displayNameSingular }}</h3>
          </div>
          <div>
            <badaso-table
              v-if="dataType.serverSide !== 1"
              v-model="selected"
              pagination
              :max-items="descriptionItems[0]"
              search
              :data="records"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('crudGenerated.footer.descriptionTitle')"
              :description-connector="
                $t('crudGenerated.footer.descriptionConnector')
              "
              :description-body="$t('crudGenerated.footer.descriptionBody')"
              multiple
            >
              <template slot="thead">
                <vs-th
                  v-for="(dataRow, index) in dataType.dataRows"
                  v-if="dataRow.browse === 1"
                  :key="index"
                  :sort-key="$caseConvert.stringSnakeToCamel(dataRow.field)"
                >
                  {{ dataRow.displayName }}
                </vs-th>
                <vs-th> {{ $t("crudGenerated.header.action") }} </vs-th>
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
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          :to="{
                            name: 'CrudGeneratedRead',
                            params: {
                              id: data[index].id,
                              slug: $route.params.slug,
                            },
                          }"
                          v-if="
                            isCanRead &&
                              $helper.isAllowedToModifyGeneratedCRUD(
                                'read',
                                dataType.name
                              )
                          "
                          icon="visibility"
                        >
                          Detail
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          :to="{
                            name: 'CrudGeneratedEdit',
                            params: {
                              id: data[index].id,
                              slug: $route.params.slug,
                            },
                          }"
                          v-if="
                            isCanEdit &&
                              $helper.isAllowedToModifyGeneratedCRUD(
                                'edit',
                                dataType
                              )
                          "
                          icon="edit"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="confirmDelete(data[index].id)"
                          v-if="
                            $helper.isAllowedToModifyGeneratedCRUD(
                              'delete',
                              dataType
                            )
                          "
                        >
                          Delete
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </badaso-table>
            <div v-else>
              <badaso-server-side-table
                v-model="selected"
                :data="records"
                stripe
                :pagination-data="data"
                :description-items="descriptionItems"
                :description-title="$t('crudGenerated.footer.descriptionTitle')"
                :description-connector="
                  $t('crudGenerated.footer.descriptionConnector')
                "
                @search="handleSearch"
                @changePage="handleChangePage"
                @changeLimit="handleChangeLimit"
                @select="handleSelect"
                @sort="handleSort"
              >
                <template slot="thead">
                  <badaso-th
                    v-for="(dataRow, index) in dataType.dataRows"
                    v-if="dataRow.browse === 1"
                    :key="`header-${index}`"
                    :sort-key="$caseConvert.stringSnakeToCamel(dataRow.field)"
                  >
                    {{ dataRow.displayName }}
                  </badaso-th>
                  <vs-th> Action </vs-th>
                </template>

                <template slot="tbody">
                  <vs-tr
                    :data="record"
                    :key="index"
                    v-for="(record, index) in records"
                  >
                    <vs-td
                      v-for="(dataRow, indexColumn) in dataType.dataRows"
                      v-if="dataRow.browse === 1"
                      :key="`${index}-${indexColumn}`"
                      :data="
                        record[
                          $caseConvert.stringSnakeToCamel(dataRow.field)
                        ]
                      "
                    >
                      <img
                        v-if="dataRow.type === 'upload_image'"
                        :src="
                          `${$api.badasoFile.view(
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
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
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
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
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
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
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
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
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
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
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
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
                    </vs-td>
                    <vs-td style="width: 1%; white-space: nowrap">
                      <badaso-dropdown vs-trigger-click>
                        <vs-button
                          size="large"
                          type="flat"
                          icon="more_vert"
                        ></vs-button>
                        <vs-dropdown-menu>
                          <badaso-dropdown-item
                            :to="{
                              name: 'CrudGeneratedRead',
                              params: {
                                id: record.id,
                                slug: $route.params.slug,
                              },
                            }"
                            v-if="
                              isCanRead &&
                                $helper.isAllowedToModifyGeneratedCRUD(
                                  'read',
                                  dataType
                                )
                            "
                            icon="visibility"
                          >
                            Detail
                          </badaso-dropdown-item>
                          <badaso-dropdown-item
                            :to="{
                              name: 'CrudGeneratedEdit',
                              params: {
                                id: record.id,
                                slug: $route.params.slug,
                              },
                            }"
                            v-if="
                              isCanEdit &&
                                $helper.isAllowedToModifyGeneratedCRUD(
                                  'edit',
                                  dataType
                                )
                            "
                            icon="edit"
                          >
                            Edit
                          </badaso-dropdown-item>
                          <badaso-dropdown-item
                            icon="delete"
                            @click="confirmDelete(record.id)"
                            v-if="
                              $helper.isAllowedToModifyGeneratedCRUD(
                                'delete',
                                dataType
                              )
                            "
                          >
                            Delete
                          </badaso-dropdown-item>
                        </vs-dropdown-menu>
                      </badaso-dropdown>
                    </vs-td>
                  </vs-tr>
                </template>
              </badaso-server-side-table>
            </div>
          </div>
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
                  $t("crudGenerated.warning.notAllowedToBrowse", {
                    tableName: dataType.displayNameSingular,
                  })
                }}
              </h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import * as _ from "lodash";
export default {
  components: {},
  name: "CrudGeneratedBrowse",
  data: () => ({
    errors: {},
    data: {},
    descriptionItems: [10, 50, 100],
    selected: [],
    records: [],
    dataType: [],
    willDeleteId: null,
    isCanAdd: false,
    isCanEdit: false,
    isCanRead: false,
    isCanSort: false,
    totalItem: 0,
    filter: "",
    page: 1,
    limit: 10,
    orderField: "",
    orderDirection: "",
    rowPerPage: null,
  }),
  watch: {
    $route: function(to, from) {
      this.getEntity();
    },
    // page: function(to, from) {
    //   this.handleChangePage(to);
    // },
    // limit: function(to, from) {
    //   this.handleChangeLimit(to);
    // },
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
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteRecord,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteRecords,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    getEntity() {
      this.$openLoader();
      this.$api.badasoEntity
        .browse({
          slug: this.$route.params.slug,
          limit: this.limit,
          page: this.page,
          filterValue: this.filter,
          orderField: this.$caseConvert.snake(this.orderField),
          orderDirection: this.$caseConvert.snake(this.orderDirection),
        })
        .then((response) => {
          this.$closeLoader();
          this.data = response.data.entities;
          this.records = response.data.entities.data;
          this.totalItem =
            response.data.entities.total > 0
              ? Math.ceil(response.data.entities.total / this.limit)
              : 1;
          this.dataType = response.data.dataType;
          let dataRows = this.dataType.dataRows.map((data) => {
            try {
              data.details = JSON.parse(data.details);
            } catch (error) {}
            return data;
          });
          this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows));

          let addFields = _.filter(dataRows, ["add", 1]);
          let editFields = _.filter(dataRows, ["edit", 1]);
          let readFields = _.filter(dataRows, ["read", 1]);

          this.isCanAdd = addFields.length > 0;
          this.isCanEdit = editFields.length > 0;
          this.isCanRead = readFields.length > 0;

          if (this.dataType.orderColumn && this.dataType.orderDisplayColumn) {
            this.isCanSort = true;
          }
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
    deleteRecord() {
      this.$openLoader();
      this.$api.badasoEntity
        .delete({
          slug: this.$route.params.slug,
          data: [
            {
              field: "id",
              value: this.willDeleteId,
            },
          ],
        })
        .then((response) => {
          this.$closeLoader();
          this.getEntity();
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteRecords() {
      const ids = this.selected.map((item) => item.id);
      this.$openLoader();
      this.$api.badasoEntity
        .deleteMultiple({
          slug: this.$route.params.slug,
          data: [
            {
              field: "ids",
              value: ids.join(","),
            },
          ],
        })
        .then((response) => {
          this.$closeLoader();
          this.getEntity();
        })
        .catch((error) => {
          this.errors = error.errors;
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
    handleSearch(e) {
      this.filter = e.target.value;
      this.page = 1;
      this.getEntity();
    },
    handleChangePage(page) {
      this.page = page;
      this.getEntity();
    },
    handleChangeLimit(limit) {
      this.page = 1;
      this.limit = limit;
      this.getEntity();
    },
    handleSort(field, direction) {
      this.orderField = field;
      this.orderDirection = direction;
      this.getEntity();
    },
    handleSelect(data) {
      this.selected = data;
    },
    displayRelationData(record, dataRow) {
      if (dataRow.relation) {
        let relationType = dataRow.relation.relationType;
        let table = this.$caseConvert.stringSnakeToCamel(
          dataRow.relation.destinationTable
        );
        let column = this.$caseConvert.stringSnakeToCamel(
          dataRow.relation.destinationTableColumn
        );
        let displayColumn = this.$caseConvert.stringSnakeToCamel(
          dataRow.relation.destinationTableDisplayColumn
        );
        if (relationType === "has_many") {
          let list = record[table];
          let flatList = list.map((ls) => {
            return ls[displayColumn];
          });
          return flatList.join(", ");
        } else {
          return record[table] ? record[table][displayColumn] : null;
        }
      } else {
        return null;
      }
    },
  },
};
</script>
