<template>
  <vs-row>
    <vs-col
      v-for="(data, index) in dashboardData"
      :key="index"
      :vs-lg="col"
      vs-xs="12"
    >
      <vs-card class="widget__content">
        <div class="widget__icon-container">
          <vs-icon
            v-if="data.icon"
            class="widget__icon"
            :icon="data.icon"
          ></vs-icon>
          <h4 class="mb-1">{{ data.value }}</h4>
          <span>{{ data.label }}</span>
        </div>
        <vs-progress
          class="widget__progress-bar"
          :percent="getPercent(data.value, data.max)"
          :color="getProgressBarColor(data.value, data.max)"
          >primary</vs-progress
        >
      </vs-card>
    </vs-col>
  </vs-row>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Home",
  components: {},
  data: () => ({
    dashboardData: [],
    col: 12,
  }),
  mounted() {
    this.getDashboardData();
    this.saveTokenFcmMessage();
  },
  methods: {
    getPercent(value, max = 100) {
      const percentage = 100 / max;
      return value * percentage;
    },
    getProgressBarColor(value, max = 100) {
      return value > max ? "danger" : "primary";
    },
    getDashboardData() {
      this.$openLoader();
      this.$api.badasoDashboard
        .index()
        .then((response) => {
          this.$closeLoader();
          this.dashboardData = response.data;
          if (this.dashboardData.length >= 4) {
            this.col = 3;
          } else if (this.dashboardData.length == 3) {
            this.col = 4;
          } else {
            this.col = 6;
          }
        })
        .catch((error) => {
          if (error.status == 401) {
            this.$closeLoader();
            this.$vs.notify({
              title: this.$t("alert.error"),
              text: error.message,
              color: "danger",
            });
          } else {
            this.$closeLoader();
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }
        });
    },
    saveTokenFcmMessage() {
      if (this.$statusActiveFeatureFirebase) {
        this.$messagingToken.then((tokenMessage) => {
          try {
            this.$api.badasoFcm.saveTokenMessage(tokenMessage);
          } catch (error) {
            console.error("Errors set token firebase cloud message :", error);
          }
        });
      }
    },
  },
};
</script>
