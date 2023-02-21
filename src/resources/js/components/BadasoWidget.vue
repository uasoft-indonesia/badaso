<template>
  <vs-col class="badaso-widget__container">
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
            <template v-if="data.delimiter == ','">
              <vs-row v-if="!data.prefixValue">
                <h4 class="mb-1">{{ setDelimiterComa(data.value) }}</h4>
              </vs-row>
              <vs-row v-else>
                <h4>{{ data.prefixValue }}</h4>
                <h4 class="mb-1">
                  {{ setDelimiterComa(data.value) }}
                </h4>
              </vs-row>
            </template>

            <template v-else-if="data.delimiter == '.'">
              <vs-row v-if="!data.prefixValue">
                <h4 class="mb-1">{{ setDelimiterPoint(data.value) }}</h4>
              </vs-row>
              <vs-row v-else>
                <h4>{{ data.prefixValue }}</h4>
                <h4 class="mb-1">
                  {{ setDelimiterPoint(data.value) }}
                </h4>
              </vs-row>
            </template>

            <template v-else>
              <vs-row v-if="!data.prefixValue">
                <h4 class="mb-1">{{ data.value }}</h4>
              </vs-row>
              <vs-row v-else>
                <h4>{{ data.prefixValue }}</h4>
                <h4 class="mb-1">
                  {{ data.value }}
                </h4>
              </vs-row>
            </template>
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
    icon: {
      type: String,
      default: "",
    },
    value: {
      type: String,
      default: "",
    },
    label: {
      type: String,
      default: "",
    },
    delimiter: {
      type: String,
      default: "",
    },
    prefixValue: {
      type: String,
      default: "",
    },
  },
};
</script>
