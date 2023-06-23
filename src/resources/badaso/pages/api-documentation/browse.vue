<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action"> </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_apidocs')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("apidocs.title") }}</h3>
          </div>
          <div>
            <iframe
              v-if="isShow"
              ref="apidocsIFrame"
              :src="urlIframe"
              class="api-documentation__iframe"
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
              <h3>{{ $t("apidocs.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "ApiDocsBrowse",
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
    urlApiDocs() {
      const apidocsRoute = import.meta.env.VITE_API_DOCS_ROUTE
        ? import.meta.env.VITE_API_DOCS_ROUTE
        : "api-docs";
      const host = window.location.origin;
      const token = localStorage.getItem("token");

      return `${host}/${apidocsRoute}?token=${token}`;
    },
  },
  methods: {
    checkApiDocs() {
      this.$openLoader();

      this.$resource
        .get(this.urlApiDocs)
        .then((result) => {
          this.urlIframe = this.urlApiDocs;
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
    this.checkApiDocs();
  },
};
</script>
