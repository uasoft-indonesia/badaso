<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="danger"
          type="relief"
          v-if="selected.length > 0 && $helper.isAllowed('delete_activitylogs')"
          @click.stop
          @click="confirmDeleteMultiple"
          ><vs-icon icon="delete_sweep"></vs-icon>
          {{ $t("action.bulkDelete") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_activitylogs')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("activityLog.title") }}</h3>
          </div>
          <div>
            <vs-table v-model="selected" :data="activitylogs" stripe>
              <template slot="header">
                <div class="con-input-search vs-table--search">
                  <input
                    type="text"
                    class="input-search vs-table--search-input"
                    v-on:keyup.enter="handleSearch"
                  />
                  <i
                    class="vs-icon notranslate icon-scale material-icons null"
                    >search</i
                  >
                </div>
              </template>
              <template slot="thead">
                <vs-th sort-key="logName">
                  {{ $t("activityLog.header.logName") }}
                </vs-th>
                <vs-th sort-key="causerType">
                  {{ $t("activityLog.header.causerType") }}
                </vs-th>
                <vs-th sort-key="causerId">
                  {{ $t("activityLog.header.causerId") }}
                </vs-th>
                <vs-th sort-key="subjectType">
                  {{ $t("activityLog.header.subjectType") }}
                </vs-th>
                <vs-th sort-key="subjectId">
                  {{ $t("activityLog.header.subjectId") }}
                </vs-th>
                <vs-th sort-key="description">
                  {{ $t("activityLog.header.description") }}
                </vs-th>
                <vs-th sort-key="createdAt">
                  {{ $t("activityLog.header.dateLogged") }}
                </vs-th>
                <vs-th> {{ $t("activityLog.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  :data="record"
                  :key="index"
                  v-for="(record, index) in activitylogs"
                >
                  <vs-td :data="record.logName">
                    {{ record.logName ? record.logName : "-" }}
                  </vs-td>
                  <vs-td :data="record.causerType">
                    {{
                      record.causerType ? record.causerType : "-"
                    }}
                  </vs-td>
                  <vs-td :data="record.causerId">
                    {{ record.causerId ? record.causerId : "-" }}
                  </vs-td>
                  <vs-td :data="record.subjectType">
                    {{
                      record.subjectType
                        ? record.subjectType
                        : "-"
                    }}
                  </vs-td>
                  <vs-td :data="record.subjectId">
                    {{
                      record.subjectId ? record.subjectId : "-"
                    }}
                  </vs-td>
                  <vs-td :data="record.description">
                    {{ record.description }}
                  </vs-td>
                  <vs-td :data="record.createdAt">
                    {{ $helper.formatDate(record.createdAt) }}
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
                            name: 'ActivityLogRead',
                            params: { id: record.id },
                          }"
                          icon="visibility"
                        >
                          Detail
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
            <div class="con-pagination-table vs-table--pagination">
              <vs-row class="mt-3"
                vs-justify="space-between"
                vs-type="flex"
                vs-w="12"
              >
                  <vs-col
                    class="vs-pagination--mb"
                    vs-type="flex"
                    vs-justify="flex-start"
                    vs-align="center"
                    vs-lg="6"
                    vs-md="12"
                    vs-sm="12"
                    vs-xs="12"
                  >
                    <div style="display: contents;">
                      <span
                        style="margin-right:5px"
                      >
                        Registries: {{ data.from }} - {{ data.to }} of {{ data.total }} | Pages:
                      </span>
                      <vs-select
                        placeholder="Row Per Page"
                        v-model="limit"
                        width="100px"
                      >
                        <vs-select-item
                          :key="index"
                          :value="item"
                          :text="item"
                          v-for="(item, index) in descriptionItems"
                        />
                      </vs-select>
                      </ul>
                    </div>
                      
                  </vs-col>
                  <vs-col class="vs-pagination--mb"
                    vs-type="flex"
                    vs-justify="flex-end"
                    vs-align="center"
                    vs-lg="6"
                    vs-md="12"
                    vs-sm="12"
                    vs-xs="12"
                  >
                    <vs-pagination
                      :total="totalItem"
                      v-model="page"
                      style="margin-bottom: 0;"
                    ></vs-pagination>
                  </vs-col>
                </vs-row>
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
              <h3>{{ $t("activityLog.warning.notAllowed") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import moment from "moment";

export default {
  name: "ActivityLogBrowse",
  components: {},
  data: () => ({
    data: {},
    selected: [],
    descriptionItems: [10, 50, 100, 200],
    activitylogs: [],
    willDeleteId: null,
    page: 1,
    limit: 10,
    totalItem: 0,
    filter: ''
  }),
  mounted() {
    this.getActivityLogList();
  },
  watch: {
    $route: function(to, from) {
      this.getEntity();
    },
    page: function(to, from) {
      this.getActivityLogList();
    },
    limit: function(to, from) {
      this.page = 1
      this.getActivityLogList();
    },
  },
  methods: {
    handleSearch(e) {
      this.filter = e.target.value;
      this.page = 1;
      this.getActivityLogList()
    },
    getActivityLogList() {
      this.$openLoader()
      this.$api.badasoActivityLog
        .browse({
          filter: this.filter,
          limit: this.limit,
          page: this.page,
        })
        .then((response) => {
          this.$closeLoader()
          this.selected = [];
          this.data = response.data
          this.activitylogs = response.data.activitylog;
          this.totalItem = response.data.total > 0
              ? Math.ceil(response.data.total / this.limit)
              : 1;
        })
        .catch((error) => {
          this.$closeLoader()
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
