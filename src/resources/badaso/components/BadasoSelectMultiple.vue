<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-select-multiple__container">
    <vs-select
      :label="label"
      :placeholder="placeholder"
      :value="value"
      width="100%"
      multiple
      autocomplete
      @input="handleInput($event)"
    >
      <vs-select-item
        v-for="(item, index) in items"
        :key="index"
        :value="item.value"
        :text="item.label"
      />
    </vs-select>
    <div v-if="additionalInfo" v-html="additionalInfo" />
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          v-for="(info, index) in alert"
          :key="index"
          class="badaso-select-multiple__input--error"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-select-multiple__input--error" v-html="alert" />
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSelectMultiple",
  components: {},
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
      default: "Select Multiple",
    },
    value: {
      type: Array,
      default: () => {
        return [];
      },
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
      type: String || Array,
      default: "",
    },
  },
  data: () => ({}),
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
