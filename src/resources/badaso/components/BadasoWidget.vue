<template>
  <vs-col class="badaso-widget__container">
    <vs-row>
      <vs-col
        v-for="(data, index) in widgets"
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
            <h4>{{ data.value }}</h4>
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
  </vs-col>
</template>

<script>
export default {
  name: "BadasoWidget",
  components: {},
  data: () => ({}),
  props: {
    widgets: {
      type: Array,
      default: () => {
        return [];
      },
    },
    col: {
      type: Number,
      default: 12,
    },
  },
  methods: {
    getPercent(value, max = 100) {
      const percentage = 100 / max;
      return value * percentage;
    },
    getProgressBarColor(value, max = 100) {
      return value > max ? "danger" : "primary";
    },
  },
};
</script>
