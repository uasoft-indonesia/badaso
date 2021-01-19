<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('read_activitylogs')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Detail Activity Log</h3>
          </div>
          <table class="table">
            <tr>
              <th>Log Name</th>
              <td>{{ activitylog.logName }}</td>
            </tr>
            <tr>
              <th>Subject Type</th>
              <td>{{ activitylog.subjectType ? activitylog.subjectType : '-' }}</td>
            </tr>
            <tr>
              <th>Subject Id</th>
              <td>{{ activitylog.subjectId ? activitylog.subjectId : '-' }}</td>
            </tr>
            <tr>
              <th>Causer Type</th>
              <td>{{ activitylog.causerType ? activitylog.causerType : '-' }}</td>
            </tr>
            <tr>
              <th>Causer Id</th>
              <td>{{ activitylog.causerId ? activitylog.causerId : '-' }}</td>
            </tr>
            <tr>
              <th>Description</th>
              <td>{{ activitylog.description }}</td>
            </tr>
            <tr>
              <th>Date Logged</th>
              <td>{{ $helper.formatDate(activitylog.createdAt) }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="6" v-if="subject">
        <vs-card>
          <div slot="header">
            <h3>Detail Subject</h3>
          </div>
          <table class="table">
            <tr v-for="(item, index) in filteredSubject" :key="index">
              <th><span style="text-transform: capitalize;">{{ index | replaceTitle }}</span></th>
              <td v-if="index === 'avatar'">
                <img
                  :src="`${$api.file.view(item)}`"
                  width="100%"
                  alt=""
                />
              </td>
              <td v-else>{{ item === null ? 'null' : item }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="6" v-if="causer">
        <vs-card>
          <div slot="header">
            <h3>Detail Causer</h3>
          </div>
          <table class="table">
            <tr v-for="(item, index) in filteredCauser" :key="index">
              <th><span style="text-transform: capitalize;">{{ index | replaceTitle }}</span></th>
              <td v-if="index === 'avatar'">
                <img
                  :src="`${$api.file.view(item)}`"
                  width="100%"
                  alt=""
                />
              </td>
              <td v-else>{{ item === null ? 'null' : item }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>

      <vs-col vs-lg="6" v-if="!$helper.isObjectEmpty(properties)">
        <vs-card>
          <div slot="header">
            <h3>Properties</h3>
          </div>
          <table class="table">
            <tr v-for="(item, index) in properties" :key="index">
              <th><span style="text-transform: capitalize;">{{ index | replaceTitle }}</span></th>
              <td v-if="typeof item === 'object'">
                <table class="table">
                  <tr v-for="(value, index) in item" :key="index" v-if="index !== 'password'">
                    <th><span style="text-transform: capitalize;">{{ index | replaceTitle }}</span></th>
                    <td>{{ value === null ? 'null' : value }}</td>
                  </tr>
                </table>
              </td>
              <td v-else>{{ item === null ? 'null' : item }}</td>
            </tr>
          </table>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";

export default {
  name: "Browse",
  components: {
    BadasoBreadcrumb,
  },
  filters: {
    replaceTitle: function (title) {
      return title.replace(/([A-Z])/g, ' $1').trim();
    }
  },
  data: () => ({
    activitylog: {},
    subject: {},
    causer: {},
    properties: {}
  }),
  computed: {
    filteredSubject: function() {
      let data = this.subject
      data.createdAt = this.$helper.formatDate(this.subject.createdAt)
      data.updatedAt = this.$helper.formatDate(this.subject.updatedAt)
      delete data.password
      delete data.emailVerifiedAt
      delete data.rememberToken
      return data
    },
    filteredCauser: function() {
      let data = this.causer
      delete data.password
      delete data.emailVerifiedAt
      delete data.rememberToken
      delete data.createdAt
      delete data.updatedAt
      return data
    }
  },
  mounted() {
    this.getActivityLogDetail();
  },
  methods: {
    getActivityLogDetail() {
      this.$vs.loading({
        type: "sound",
      });
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
          console.log('LOG', response.data.properties);
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