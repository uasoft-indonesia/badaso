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
              style="width: 100%; height: 750px; overflow: hidden; border: none;"
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
      let log_viewer_route = process.env.MIX_LOG_VIEWER_ROUTE
        ? process.env.MIX_LOG_VIEWER_ROUTE
        : "log-viewer";
      let host = window.location.origin;
      let token = localStorage.getItem("token");

      return `${host}/${log_viewer_route}?token=${token}`;
    },
  },
  methods: {
    async checkLogViewer() {
      this.$openLoader();

      try {
        let response;
        const request = await fetch(this.urlLogViewer);
        this.statusCode = request.status;
        response = await request.text();

        if (this.statusCode >= 400) {
          let { message, errors, data } = JSON.parse(response);
          this.message = message;
          this.isShow = false;
        } else {
          this.urlIframe = this.urlLogViewer;
        }
      } catch (error) {
        this.isShow = false;
      }

      this.$closeLoader();
    },
  },
  created(){
    this.checkLogViewer()
  }
};
</script>
