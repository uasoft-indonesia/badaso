<template>
  <vs-col :vs-lg="size" class="mb-3">
    <!-- <vs-input
        type="time"
        :label="label"
        :value="value"
        @input="handleInput($event)"
    /> -->
    <label for="" class="vs-input--label">{{ label }}</label>
    <div class="datetime-box">
      <datetime
        :label="label"
        type="datetime"
        :title="label"
        :value="value"
        class="datetime-input"
        @input="handleInput($event)"
      ></datetime>
      <vs-icon icon="calendar_today" class="datetime-icon"></vs-icon>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoDatetime",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Date Time",
    },
    placeholder: {
      type: String,
      default: "Date Time",
    },
    value: {
      type: String,
      required: true,
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
