<template>
  <div>
    <template v-if="!showMaintenancePage">
      <badaso-breadcrumb-hover full :visibleButtonAction="selected.length != 0">
        <template slot="action">
          <badaso-dropdown-item
            icon="list"
            :to="{ name: 'CrudGeneratedSort' }"
            v-if="
              isCanSort &&
              $helper.isAllowedToModifyGeneratedCRUD('edit', dataType)
            "
          >
            {{ $t("action.sort") }}
          </badaso-dropdown-item>
          <badaso-dropdown-item
            icon="delete_sweep"
            v-if="
              selected.length > 0 &&
              $helper.isAllowedToModifyGeneratedCRUD('delete', dataType)
            "
            @click.stop
            @click="confirmDeleteMultiple"
          >
            {{ $t("action.bulkDelete") }} Permanent
          </badaso-dropdown-item>
          <badaso-dropdown-item
            icon="restore"
            v-if="selected.length > 0 && isShowDataRecycle"
            @click.stop
            @click="confirmRestoreMultiple"
          >
            {{ $t("action.bulkRestore") }}
          </badaso-dropdown-item>
        </template>
      </badaso-breadcrumb-hover>

      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('browse', dataType)">
        <vs-col vs-lg="12">
          <vs-alert
            :active="Object.keys(errors).length > 0"
            color="danger"
            icon="new_releases"
            style="margin-bottom: 20px"
          >
            <span v-for="key in Object.keys(errors)" :key="key">
              <span v-for="err in errors[key]" :key="err">
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
                :description-title="`${$t(
                  'crudGenerated.footer.descriptionTitle'
                )} Permanent`"
                :description-connector="
                  $t('crudGenerated.footer.descriptionConnector')
                "
                :description-body="$t('crudGenerated.footer.descriptionBody')"
                multiple
              >
                <template slot="thead">
                  <vs-th
                    v-for="(dataRow, index) in dataType.dataRows"
                    :key="index"
                    :sort-key="$caseConvert.stringSnakeToCamel(dataRow.field)"
                  >
                    <template v-if="dataRow.browse == 1">
                      {{ dataRow.displayName }}
                    </template>
                  </vs-th>
                  <vs-th> {{ $t("crudGenerated.header.action") }} </vs-th>
                </template>

                <template slot-scope="{ data }">
                  <vs-tr
                    :data="record"
                    :key="index"
                    v-for="(record, index) in data"
                    :state="
                      idsOfflineDeleteRecord.includes(record.id.toString())
                        ? 'danger'
                        : 'default'
                    "
                  >
                    <template
                      v-if="
                        !idsOfflineDeleteRecord.includes(
                          record.id.toString()
                        ) || !isOnline
                      "
                    >
                      <vs-td
                        v-for="(dataRow, indexColumn) in dataType.dataRows"
                        :key="indexColumn"
                        :data="
                          data[index][
                            $caseConvert.stringSnakeToCamel(dataRow.field)
                          ]
                        "
                      >
                        <template v-if="dataRow.browse == 1">
                          <img
                            v-if="dataRow.type == 'upload_image'"
                            :src="`${
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            }`"
                            width="100%"
                            alt=""
                          />
                          <div
                            v-else-if="dataRow.type == 'upload_image_multiple'"
                            style="width: 100%"
                          >
                            <img
                              v-for="(image, indexImage) in stringToArray(
                                record[
                                  $caseConvert.stringSnakeToCamel(dataRow.field)
                                ]
                              )"
                              :key="indexImage"
                              :src="`${image}`"
                              width="100%"
                              alt=""
                              style="margin-bottom: 10px"
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
                            :href="`${$api.badasoFile.download(
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            )}`"
                            target="_blank"
                            >{{
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            }}</a
                          >
                          <div
                            v-else-if="dataRow.type == 'upload_file_multiple'"
                            style="width: 100%"
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
                              dataRow.type == 'radio' ||
                              dataRow.type == 'select'
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
                              {{
                                bindSelection(dataRow.details.items, selected)
                              }}
                            </p>
                          </div>
                          <div v-else-if="dataRow.type == 'color_picker'">
                            <div
                              :style="`width: 100%; height: 14px; background-color: ${
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
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          }}</span>
                        </template>
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
                                ) &&
                                !isShowDataRecycle
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
                                ) &&
                                !isShowDataRecycle
                              "
                              icon="edit"
                            >
                              Edit
                            </badaso-dropdown-item>
                            <badaso-dropdown-item
                              icon="delete"
                              @click="confirmDelete(data[index].id)"
                              v-if="
                                !idsOfflineDeleteRecord.includes(
                                  record.id.toString()
                                ) &&
                                $helper.isAllowedToModifyGeneratedCRUD(
                                  'delete',
                                  dataType
                                )
                              "
                            >
                              Delete Permanent
                            </badaso-dropdown-item>
                            <badaso-dropdown-item
                              @click="confirmDeleteDataPending(data[index].id)"
                              icon="delete_outline"
                              v-if="
                                idsOfflineDeleteRecord.includes(
                                  record.id.toString()
                                ) && !isShowDataRecycle
                              "
                            >
                              {{
                                $t(
                                  "offlineFeature.crudGenerator.deleteDataPending"
                                )
                              }}
                            </badaso-dropdown-item>
                            <badaso-dropdown-item
                              @click="
                                confirmRestoreDataSoftDelete(data[index].id)
                              "
                              icon="restore"
                              v-if="isShowDataRecycle"
                            >
                              {{ $t("softDelete.crudGenerator.restore") }}
                            </badaso-dropdown-item>
                          </vs-dropdown-menu>
                        </badaso-dropdown>
                      </vs-td>
                    </template>
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
                  :description-title="
                    $t('crudGenerated.footer.descriptionTitle')
                  "
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
                      :key="`header-${index}`"
                      :sort-key="$caseConvert.stringSnakeToCamel(dataRow.field)"
                    >
                      <template v-if="dataRow.browse == 1">
                        {{ dataRow.displayName }}
                      </template>
                    </badaso-th>
                    <vs-th> Action </vs-th>
                  </template>

                  <template slot="tbody">
                    <vs-tr
                      :data="record"
                      :key="index"
                      v-for="(record, index) in records"
                      :state="
                        idsOfflineDeleteRecord.includes(record.id.toString())
                          ? 'danger'
                          : 'default'
                      "
                    >
                      <template
                        v-if="
                          !idsOfflineDeleteRecord.includes(
                            record.id.toString()
                          ) || !isOnline
                        "
                      >
                        <vs-td
                          v-for="(dataRow, indexColumn) in dataType.dataRows"
                          :key="`${index}-${indexColumn}`"
                          :data="
                            record[
                              $caseConvert.stringSnakeToCamel(dataRow.field)
                            ]
                          "
                        >
                          <template v-if="dataRow.browse == 1">
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
                              v-else-if="
                                dataRow.type == 'upload_image_multiple'
                              "
                              style="width: 100%"
                            >
                              <img
                                v-for="(image, indexImage) in stringToArray(
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
                                  ]
                                )"
                                :key="indexImage"
                                :src="`${image}`"
                                width="100%"
                                alt=""
                                style="margin-bottom: 10px"
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
                              :href="`${$api.badasoFile.download(
                                getDownloadUrl(
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
                                  ]
                                )
                              )}`"
                              target="_blank"
                              >{{
                                getDownloadUrl(
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
                                  ]
                                )
                              }}</a
                            >
                            <div
                              v-else-if="dataRow.type == 'upload_file_multiple'"
                              style="width: 100%"
                            >
                              <p
                                v-for="(file, indexFile) in stringToArray(
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
                                  ]
                                )"
                                :key="indexFile"
                              >
                                <a
                                  :href="`${$api.badasoFile.download(
                                    getDownloadUrl(file)
                                  )}`"
                                  target="_blank"
                                  >{{ getDownloadUrl(file) }}</a
                                >
                              </p>
                            </div>
                            <p
                              v-else-if="
                                dataRow.type == 'radio' ||
                                dataRow.type == 'select'
                              "
                            >
                              {{
                                bindSelection(
                                  dataRow.details.items,
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
                                  ]
                                )
                              }}
                            </p>
                            <div
                              v-else-if="
                                dataRow.type == 'select_multiple' ||
                                dataRow.type == 'checkbox'
                              "
                              style="width: 100%"
                            >
                              <p
                                v-for="(
                                  selected, indexSelected
                                ) in stringToArray(
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
                                  ]
                                )"
                                :key="indexSelected"
                              >
                                {{
                                  bindSelection(dataRow.details.items, selected)
                                }}
                              </p>
                            </div>
                            <div v-else-if="dataRow.type == 'color_picker'">
                              <div
                                :style="`width: 100%; height: 14px; background-color: ${
                                  record[
                                    $caseConvert.stringSnakeToCamel(
                                      dataRow.field
                                    )
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
                              record[
                                $caseConvert.stringSnakeToCamel(dataRow.field)
                              ]
                            }}</span>
                          </template>
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
                                  !idsOfflineDeleteRecord.includes(
                                    record.id.toString()
                                  ) &&
                                  $helper.isAllowedToModifyGeneratedCRUD(
                                    'delete',
                                    dataType
                                  )
                                "
                              >
                                Delete
                              </badaso-dropdown-item>
                              <badaso-dropdown-item
                                @click="confirmDeleteDataPending(record.id)"
                                icon="delete_outline"
                                v-if="
                                  idsOfflineDeleteRecord.includes(
                                    record.id.toString()
                                  )
                                "
                              >
                                {{
                                  $t(
                                    "offlineFeature.crudGenerator.deleteDataPending"
                                  )
                                }}
                              </badaso-dropdown-item>
                            </vs-dropdown-menu>
                          </badaso-dropdown>
                        </vs-td>
                      </template>
                    </vs-tr>
                  </template>
                </badaso-server-side-table>
              </div>
            </div>
          </vs-card>
        </vs-col>
        <vs-prompt
          @accept="saveMaintenanceState"
          :active.sync="maintenanceDialog"
          class="mb-0"
        >
          <vs-row class="mb-0">
            <badaso-switch
              :label="$t('crudGenerated.maintenanceDialog.switch')"
              :placeholder="$t('crudGenerated.maintenanceDialog.switch')"
              v-model="isMaintenance"
              size="12"
              :alert="errors['is_maintenance']"
            ></badaso-switch>
          </vs-row>
        </vs-prompt>
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
    </template>
    <template v-if="showMaintenancePage">
      <badaso-breadcrumb-row full> </badaso-breadcrumb-row>

      <vs-row v-if="$helper.isAllowedToModifyGeneratedCRUD('browse', dataType)">
        <vs-col vs-lg="12">
          <div
            class="flex flex-direction-column justify-content-center align-items-center"
          >
            <img src="/badaso-images/maintenance.png" alt="Maintenance Icon" />

            <h1 class="mt-4 text-center">We are under <br />maintenance</h1>
          </div>
        </vs-col>
      </vs-row>
    </template>
  </div>
</template>

<script>
import * as _ from "lodash";
import jsPDF from "jspdf";
import "jspdf-autotable";
export default {
  name: "CrudGeneratedBrowseBin",
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
    fieldsForExcel: {},
    fieldsForPdf: [],
    idsOfflineDeleteRecord: [],
    maintenanceDialog: false,
    isMaintenance: false,
    showMaintenancePage: false,
    isShowDataRecycle: true,
  }),
  watch: {
    $route: function (to, from) {
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
    this.loadIdsOfflineDelete();
  },
  methods: {
    getDownloadUrl(item) {
      if (item == null || item == undefined) return;

      return item.split("storage").pop();
    },
    confirmDeleteDataPending(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: () => this.deleteRecordDataPending(id),
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmRestoreDataSoftDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "success",
        title: this.$t("action.restore.title"),
        text: this.$t("action.restore.text"),
        accept: () => this.deleteRestoreDataSoftDelete(id),
        acceptText: this.$t("action.restore.accept"),
        cancelText: this.$t("action.restore.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
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
    confirmRestoreMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "success",
        title: this.$t("action.restore.title"),
        text: this.$t("action.restore.text"),
        accept: this.restoreRecords,
        acceptText: this.$t("action.restore.accept"),
        cancelText: this.$t("action.restore.cancel"),
        cancel: () => {},
      });
    },
    async getEntity() {
      this.$openLoader();
      try {
        const response = await this.$api.badasoEntity.browse({
          slug: this.$route.params.slug,
          limit: this.limit,
          page: this.page,
          filterValue: this.filter,
          orderField: this.$caseConvert.snake(this.orderField),
          orderDirection: this.$caseConvert.snake(this.orderDirection),
          showSoftDelete: this.isShowDataRecycle,
        });
        const {
          data: { dataType },
        } = await this.$api.badasoTable.getDataType({
          slug: this.$route.params.slug,
        });
        this.$closeLoader();
        this.data = response.data;
        this.records = response.data.data;
        this.totalItem =
          response.data.total > 0
            ? Math.ceil(response.data.total / this.limit)
            : 1;
        this.dataType = dataType;
        this.isMaintenance = this.dataType.isMaintenance == 1;
        const dataRows = this.dataType.dataRows.map((data) => {
          try {
            data.details = JSON.parse(data.details);
          } catch (error) {}
          return data;
        });
        this.dataType.dataRows = JSON.parse(JSON.stringify(dataRows));
        const addFields = _.filter(dataRows, ["add", 1]);
        const editFields = _.filter(dataRows, ["edit", 1]);
        const readFields = _.filter(dataRows, ["read", 1]);
        this.isCanAdd = addFields.length > 0;
        this.isCanEdit = editFields.length > 0;
        this.isCanRead = readFields.length > 0;
        if (this.dataType.orderColumn && this.dataType.orderDisplayColumn) {
          this.isCanSort = true;
        }
        this.prepareExcelExporter();
      } catch (error) {
        if (error.status == 503) {
          this.showMaintenancePage = true;
        }
        this.$closeLoader();
        this.$vs.notify({
          title: this.$t("alert.danger"),
          text: error.message,
          color: "danger",
        });
      }
    },
    deleteRecordDataPending(id) {
      try {
        const keyStore = window.location.pathname;
        this.$readObjectStore(keyStore).then((store) => {
          if (store.result) {
            const data = store.result.data;
            const newData = [];

            for (const indexData in data) {
              const itemData = data[indexData].requestData.data;
              for (const indexItem in itemData) {
                const fieldData = itemData[indexItem];
                if (fieldData.field == "ids") {
                  let valueIds = fieldData.value.split(",");
                  valueIds = valueIds.filter((valueId, index) => valueId != id);
                  if (valueIds.length != 0) {
                    data[indexData].requestData.data[indexItem].value =
                      valueIds.join(",");

                    newData[newData.length] = data[indexData];
                  }
                } else {
                  const valueId = fieldData.value;
                  if (valueId.toString() != id.toString()) {
                    newData[newData.length] = data[indexData];
                  }
                }
              }
            }

            this.$setObjectStore(keyStore, { data: newData });

            this.idsOfflineDeleteRecord = this.idsOfflineDeleteRecord.filter(
              (itemId, index) => itemId != id
            );
          }
        });
      } catch (error) {
        console.error(error);
      }
    },
    deleteRestoreDataSoftDelete(id) {
      this.$openLoader();
      this.$api.badasoEntity
        .restore({
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
          this.loadIdsOfflineDelete();

          this.errors = error.errors;
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
          isHardDelete: true,
        })
        .then((response) => {
          this.$closeLoader();
          this.getEntity();
        })
        .catch((error) => {
          this.loadIdsOfflineDelete();

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
          isHardDelete: true,
        })
        .then((response) => {
          this.$closeLoader();
          this.getEntity();
        })
        .catch((error) => {
          this.selected = [];
          this.loadIdsOfflineDelete();

          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    restoreRecords() {
      const ids = this.selected.map((item) => item.id);
      this.$openLoader();
      this.$api.badasoEntity
        .restoreMultiple({
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
          this.selected = [];
          this.loadIdsOfflineDelete();

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
        const relationType = dataRow.relation.relationType;
        const table = this.$caseConvert.stringSnakeToCamel(
          dataRow.relation.destinationTable
        );
        this.$caseConvert.stringSnakeToCamel(
          dataRow.relation.destinationTableColumn
        );
        const displayColumn = this.$caseConvert.stringSnakeToCamel(
          dataRow.relation.destinationTableDisplayColumn
        );

        if (relationType == "has_many") {
          const list = record[table];
          const flatList = list.map((ls) => {
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
    prepareExcelExporter() {
      for (const iterator of this.dataType.dataRows) {
        this.fieldsForExcel[iterator.displayName] =
          this.$caseConvert.stringSnakeToCamel(iterator.field);
      }

      for (const iterator of this.dataType.dataRows) {
        const string = this.$caseConvert.stringSnakeToCamel(iterator.field);
        if (iterator.browse == 1) {
          this.fieldsForPdf.push(
            string.charAt(0).toUpperCase() + string.slice(1)
          );
        }
      }
    },
    openMaintenanceDialog() {
      this.maintenanceDialog = true;
    },
    saveMaintenanceState() {
      this.$api.badasoEntity
        .maintenance({
          slug: this.$route.params.slug,
          is_maintenance: this.isMaintenance,
        })
        .then((response) => {
          this.maintenanceDialog = false;
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
    generatePdf() {
      let data = this.records;

      const result = data.map(Object.values);

      // eslint-disable-next-line new-cap
      const doc = new jsPDF("l");

      doc.autoTable({
        head: [this.fieldsForPdf],
        body: result,
        startY: 15,
        // Default for all columns
        styles: { valign: "middle" },
        headStyles: { fillColor: [6, 187, 211] },
        // Override the default above for the text column
        columnStyles: { text: { cellWidth: "wrap" } },
      });

      const output = doc.output("blob");

      if (window.navigator && window.navigator.msSaveOrOpenBlob) {
        window.navigator.msSaveOrOpenBlob(output);
        return;
      }

      data = window.URL.createObjectURL(output);
      const link = document.createElement("a");
      link.href = data;
      link.download = this.dataType.displayNameSingular + ".pdf";
      link.click();
      setTimeout(function () {
        // For Firefox it is necessary to delay revoking the ObjectURL
        window.URL.revokeObjectURL(data);
      }, 100);
    },
    loadIdsOfflineDelete() {
      try {
        const keyStore = window.location.pathname;
        this.$readObjectStore(keyStore).then((store) => {
          let dataResult = store.result;
          if (dataResult) {
            dataResult = dataResult.data;
            const newDataResult = [];
            for (const index in dataResult) {
              const { requestMethod, requestData } = dataResult[index];
              if (requestMethod == "delete" && requestData.slug != undefined) {
                newDataResult[newDataResult.length] = dataResult[index];
              }
            }

            let ids = [];
            for (const index in newDataResult) {
              const dataRequest = newDataResult[index].requestData.data;
              for (const indexDataRequest in dataRequest) {
                if (
                  dataRequest[indexDataRequest].field == "id" ||
                  dataRequest[indexDataRequest].field == "ids"
                ) {
                  const valueIds = dataRequest[indexDataRequest].value
                    .toString()
                    .split(",");
                  ids.push(...valueIds);
                }
              }
            }
            ids = ids.filter((itemIds, indexIds, self) => {
              return self.indexOf(itemIds) == indexIds;
            });

            this.idsOfflineDeleteRecord = ids;
          }
        });
      } catch (error) {
        console.error(error);
      }
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
