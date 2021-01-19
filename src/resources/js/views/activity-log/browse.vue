<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button
            color="danger"
            type="relief"
            v-if="
              selected.length > 0 && $helper.isAllowed('delete_activitylogs')
            "
            @click.stop
            @click="confirmDeleteMultiple"
            ><vs-icon icon="delete_sweep"></vs-icon> Bulk Delete</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('browse_activitylogs')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Activity Log</h3>
          </div>
          <div>
            <vs-table
              v-model="selected"
              pagination
              max-items="50"
              search
              :data="activitylogs"
              stripe
              description
              :description-items="descriptionItems"
              description-title="Registries"
              description-connector="of"
              description-body="Pages"
            >
              <template slot="thead">
                <vs-th sort-key="logName"> Log Name </vs-th>
                <vs-th sort-key="causerType"> Causer </vs-th>
                <vs-th sort-key="causerId"> Causer Id</vs-th>
                <vs-th sort-key="subjectType"> Subject </vs-th>
                <vs-th sort-key="subjectId"> Subject Id </vs-th>
                <vs-th sort-key="description"> Description </vs-th>
                <vs-th sort-key="createdAt"> Date Logged </vs-th>
                <vs-th> Action </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].logName">
                    {{ data[indextr].logName ? data[indextr].logName : '-'  }}
                  </vs-td>
                  <vs-td :data="data[indextr].causerType">
                    {{ data[indextr].causerType ? data[indextr].causerType : '-'  }}
                  </vs-td>
                  <vs-td :data="data[indextr].causerId">
                    {{ data[indextr].causerId ? data[indextr].causerId : '-' }}
                  </vs-td>
                  <vs-td :data="data[indextr].subjectType">
                    {{ data[indextr].subjectType ? data[indextr].subjectType : '-' }}
                  </vs-td>
                  <vs-td :data="data[indextr].subjectId">
                    {{ data[indextr].subjectId ? data[indextr].subjectId : '-' }}
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
              <h3>You're not allowed to browse Activity Log</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";
import moment from 'moment';

export default {
  name: "Browse",
  components: {
    BadasoBreadcrumb,
  },
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    activitylogs: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getActivityLogList();
  },
  methods: {
    getActivityLogList() {
      this.$vs.loading({
        type: "sound",
      });
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
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
