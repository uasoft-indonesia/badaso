<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <label v-if="label != ''"  for="" class="vs-input--label">{{ label }}</label>
    <div class="datetime-box">
      <datetime
        :label="label"
        type="time"
        :title="label"
        :value="value"
        class="datetime-input"
        @input="handleInput($event)"
      ></datetime>
      <div class="datetime-icon-box">
        <vs-icon icon="schedule" class="datetime-icon"></vs-icon>
        </diV>
    </div>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p
          class="text-danger"
          v-for="(info, index) in alert"
          :key="index"
          v-html="info + '<br />'"
        ></p>
      </div>
      <div v-else>
        <span class="text-danger" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoTime",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "",
    },
    placeholder: {
      type: String,
      default: "Time",
    },
    value: {
      type: String,
      required: true,
      default: "",
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String | Array,
      default: "",
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
<style>
.vdatetime-input {
  width: 100%;
  padding: 0.4rem;
  border: 1px solid rgba(0, 0, 0, 0.2);
}
</style>
