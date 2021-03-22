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
            <vs-table
              v-model="selected"
              pagination
              :max-items="descriptionItems[0]"
              search
              :data="activitylogs"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('activityLog.footer.descriptionTitle')"
              :description-connector="
                $t('activityLog.footer.descriptionConnector')
              "
              :description-body="$t('activityLog.footer.descriptionBody')"
            >
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
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].logName">
                    {{ data[indextr].logName ? data[indextr].logName : "-" }}
                  </vs-td>
                  <vs-td :data="data[indextr].causerType">
                    {{
                      data[indextr].causerType ? data[indextr].causerType : "-"
                    }}
                  </vs-td>
                  <vs-td :data="data[indextr].causerId">
                    {{ data[indextr].causerId ? data[indextr].causerId : "-" }}
                  </vs-td>
                  <vs-td :data="data[indextr].subjectType">
                    {{
                      data[indextr].subjectType
                        ? data[indextr].subjectType
                        : "-"
                    }}
                  </vs-td>
                  <vs-td :data="data[indextr].subjectId">
                    {{
                      data[indextr].subjectId ? data[indextr].subjectId : "-"
                    }}
                  </vs-td>
                  <vs-td :data="data[indextr].description">
                    {{ data[indextr].description }}
                  </vs-td>
                  <vs-td :data="data[indextr].createdAt">
                    {{ $helper.formatDate(data[indextr].createdAt) }}
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <vs-button
                      color="success"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'ActivityLogRead',
                        params: { id: data[indextr].id },
                      }"
                      v-if="$helper.isAllowed('read_activitylogs')"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
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
  components: {
  },
  data: () => ({
    selected: [],
    descriptionItems: [50, 100, 200],
    activitylogs: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getActivityLogList();
  },
  methods: {
    getActivityLogList() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.activitylog
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.selected = [];
          this.activitylogs = response.data.activitylog;
        })
        .catch((error) => {
          this.$vs.loading.close();
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
