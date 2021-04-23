<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action"> </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_file_manager')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("fileManager.title") }}</h3>
          </div>
          <div v-if="isShowIFrame">
            <iframe
              :src="urlIframe"
              style="width: 100%; height: 700px; overflow: hidden; border: none;"
            />
          </div>
          <div v-else>
            {{ message }}
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("fileManager.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
export default {
  name: "FileManagerBrowse",
  components: {},
  data() {
    return {
      statusCode: null,
      message: null,
    };
  },
  async created() {
    await this.requestCheckPageIFrame()
  },
  methods: {
    async requestCheckPageIFrame() {
      this.$openLoader();
      let response;
      const request = await fetch(this.urlIframe);
      this.statusCode = request.status;
      response = await request.text();

      if (this.statusCode >= 400) {
        let { message, errors, data } = JSON.parse(response);
        this.message = message;
      }
      this.$closeLoader();
    },
  },
  computed: {
    urlIframe() {
      let host = window.location.origin;
      let token = localStorage.getItem("token");
      let url = `${host}/filemanager?token=${token}`;
      return url;
    },
    isShowIFrame() {
      return this.statusCode == 200;
    },
  },
};
</script>
