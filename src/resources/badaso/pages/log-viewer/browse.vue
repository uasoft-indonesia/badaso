<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action"> </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_logviewer')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("logViewer.title") }}</h3>
          </div>
          <div>
            <iframe
              v-if="isShow"
              ref="logViewerIFrame"
              :src="urlIframe"
              class="log-viewer__iframe"
            ></iframe>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("logViewer.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "LogViewerBrowse",
  components: {},
  data() {
    return {
      urlIframe: null,
      statusCode: null,
      message: null,
      isShow: true,
    };
  },
  computed: {
    urlLogViewer() {
      const logViewerRoute = import.meta.env.VITE_LOG_VIEWER_ROUTE
        ? import.meta.env.VITE_LOG_VIEWER_ROUTE
        : "log-viewer";
      const host = window.location.origin;
      const token = localStorage.getItem("token");

      return `${host}/${logViewerRoute}?token=${token}`;
    },
  },
  methods: {
    checkLogViewer() {
      this.$openLoader();

      this.$resource
        .get(this.urlLogViewer)
        .then((result) => {
          this.urlIframe = this.urlLogViewer;
          this.isShow = true;
        })
        .catch((error) => {
          const { message } = error;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: message,
            color: "danger",
          });
          this.isShow = false;
        })
        .finally(() => {
          this.$closeLoader();
        });
    },
  },
  created() {
    this.checkLogViewer();
  },
};
</script>
