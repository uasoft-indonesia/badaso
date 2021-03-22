<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('read_activitylogs')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("activityLog.detail.title") }}</h3>
          </div>
          <table class="table">
            <tr>
              <th>{{ $t("activityLog.header.logName") }}</th>
              <td>{{ activitylog.logName }}</td>
            </tr>
            <tr>
              <th>{{ $t("activityLog.header.subjectType") }}</th>
              <td>
                {{ activitylog.subjectType ? activitylog.subjectType : "-" }}
              </td>
            </tr>
            <tr>
              <th>{{ $t("activityLog.header.subjectId") }}</th>
              <td>{{ activitylog.subjectId ? activitylog.subjectId : "-" }}</td>
            </tr>
            <tr>
              <th>{{ $t("activityLog.header.causerType") }}</th>
              <td>
                {{ activitylog.causerType ? activitylog.causerType : "-" }}
              </td>
            </tr>
            <tr>
              <th>{{ $t("activityLog.header.causerId") }}</th>
              <td>{{ activitylog.causerId ? activitylog.causerId : "-" }}</td>
            </tr>
            <tr>
              <th>{{ $t("activityLog.header.description") }}</th>
              <td>{{ activitylog.description }}</td>
            </tr>
            <tr>
              <th>{{ $t("activityLog.header.dateLogged") }}</th>
              <td>{{ $helper.formatDate(activitylog.createdAt) }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="6" vs-xs="12" v-if="subject">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("activityLog.detail.subject.title") }}</h3>
          </div>
          <table class="table">
            <tr v-for="(item, index) in filteredSubject" :key="index">
              <th>
                <span style="text-transform: capitalize;">{{
                  index | replaceTitle
                }}</span>
              </th>
              <td v-if="index === 'avatar'">
                <img :src="`${$api.file.view(item)}`" width="100%" alt="" />
              </td>
              <td v-else>{{ item === null ? "null" : item }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="6" vs-xs="12" v-if="causer">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("activityLog.detail.causer.title") }}</h3>
          </div>
          <table class="table">
            <tr v-for="(item, index) in filteredCauser" :key="index">
              <th>
                <span style="text-transform: capitalize;">{{
                  index | replaceTitle
                }}</span>
              </th>
              <td v-if="index === 'avatar'">
                <img :src="`${$api.file.view(item)}`" width="100%" alt="" />
              </td>
              <td v-else>{{ item === null ? "null" : item }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>

      <vs-col vs-lg="6" vs-xs="12" v-if="!$helper.isObjectEmpty(properties)">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("activityLog.detail.properties.title") }}</h3>
          </div>
          <table class="table">
            <tr v-for="(item, index) in properties" :key="index">
              <th>
                <span style="text-transform: capitalize;">{{
                  index | replaceTitle
                }}</span>
              </th>
              <td v-if="typeof item === 'object'">
                <table class="table">
                  <tr
                    v-for="(value, index) in item"
                    :key="index"
                    v-if="index !== 'password'"
                  >
                    <th>
                      <span style="text-transform: capitalize;">{{
                        index | replaceTitle
                      }}</span>
                    </th>
                    <td>{{ value === null ? "null" : value }}</td>
                  </tr>
                </table>
              </td>
              <td v-else>{{ item === null ? "null" : item }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "ActivityLogRead",
  components: {
  },
  filters: {
    replaceTitle: function(title) {
      return title.replace(/([A-Z])/g, " $1").trim();
    },
  },
  data: () => ({
    activitylog: {},
    subject: {},
    causer: {},
    properties: {},
  }),
  computed: {
    filteredSubject: function() {
      let data = this.subject;
      data.createdAt = this.$helper.formatDate(this.subject.createdAt);
      data.updatedAt = this.$helper.formatDate(this.subject.updatedAt);
      delete data.password;
      delete data.emailVerifiedAt;
      delete data.rememberToken;
      return data;
    },
    filteredCauser: function() {
      let data = this.causer;
      delete data.password;
      delete data.emailVerifiedAt;
      delete data.rememberToken;
      delete data.createdAt;
      delete data.updatedAt;
      return data;
    },
  },
  mounted() {
    this.getActivityLogDetail();
  },
  methods: {
    getActivityLogDetail() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.activitylog
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.activitylog = response.data.activitylog;
          this.subject = response.data.subject;
          this.causer = response.data.causer;
          this.properties = response.data.properties;
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

<style>
.vs-card--content {
  overflow: auto;
}
</style>
