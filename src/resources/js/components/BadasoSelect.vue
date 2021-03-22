<template>
  <vs-col :vs-lg="size" vs-xs="12" class="mb-3">
    <vs-select
      :label="label"
      :placeholder="placeholder"
      :value="value"
      @input="handleInput($event)"
      width="100%"
      @change="handleChange($event)"
    >
      <vs-select-item
        :key="index"
        :value="item.value ? item.value : item"
        :text="item.label ? item.label : item"
        v-for="(item, index) in items"
      />
    </vs-select>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <p class="text-danger" v-for="(info, index) in alert" :key="index" v-html="info+'<br />'"></p>
      </div>
      <div v-else>
        <span class="text-danger" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSelect",
  components: {},
  data: () => ({}),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Select",
    },
    placeholder: {
      type: String,
      default: "Select",
    },
    value: {
      type: String,
      required: true,
      default: "",
    },
    items: {
      type: Array,
      required: true,
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String|Array,
      default: "",
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
    handleChange(val) {
      this.$emit("onChange");
    }
  },
};
</script>