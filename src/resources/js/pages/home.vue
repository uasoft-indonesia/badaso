<template>
  <vs-row style="display: block;">
    <vs-col
      v-for="(data, index) in dashboardData"
      :key="index"
      :vs-lg="col"
      vs-xs="12"
    >
      <vs-card>
        <h4 class="mb-1">{{ data.value }}</h4>
        <span>{{ data.label }}</span>
        <vs-progress :percent="70" color="primary">primary</vs-progress>
      </vs-card>
    </vs-col>
  </vs-row>
</template>

<script>
export default {
  name: "Home",
  components: {},
  data: () => ({
    dashboardData: [],
    col: 12,
  }),
  mounted() {
    this.getDashboardData();
  },
  methods: {
    getDashboardData() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.dashboard
        .index()
        .then((response) => {
          this.$vs.loading.close();
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
