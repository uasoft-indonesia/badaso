<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-switch__container">
    <div v-if="label == 'Maintenance for all pages.' && status">
      <label v-if="label != ''" for="" class="badaso-switch__label"
        >{{ label }}
        <span style="color: red"
          >({{ $t("site.maintenanceMode") }}
          <a
            href="https://badaso-docs.uatech.co.id/getting-started/configuration#maintenance"
            >{{ $t("database.browse.fieldNotSupport.button.visitDocs") }}</a
          >)</span
        >
        <vs-tooltip :text="tooltip" v-if="tooltip">
          <vs-icon icon="help_outline" size="16px" color="#A5A5A5"></vs-icon>
        </vs-tooltip>
      </label>
    </div>
    <div v-else>
      <label v-if="label != ''" for="" class="badaso-switch__label"
        >{{ label }}
        <vs-tooltip :text="tooltip" v-if="tooltip">
          <vs-icon icon="help_outline" size="16px" color="#A5A5A5"></vs-icon>
        </vs-tooltip>
      </label>
    </div>
    <div v-if="label == 'Maintenance for all pages.' && status === 'true'">
      <vs-switch
        :value="true"
        @change="onChange"
        :disabled="status === 'true'"
        @input="handleInput($event)"
      >
        <span slot="on">{{ onLabel }}</span>
        <span slot="off">{{ offLabel }}</span>
      </vs-switch>
    </div>
    <div
      v-else-if="label == 'Maintenance for all pages.' && status === 'false'"
    >
      <vs-switch :value="false" @change="onChange" @input="handleInput($event)">
        <span slot="on">{{ onLabel }}</span>
        <span slot="off">{{ offLabel }}</span>
      </vs-switch>
    </div>
    <div v-else>
      <vs-switch :value="value" @change="onChange" @input="handleInput($event)">
        <span slot="on">{{ onLabel }}</span>
        <span slot="off">{{ offLabel }}</span>
      </vs-switch>
    </div>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-switch__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-switch__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
export default {
  name: "BadasoSwitch",
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
      default: "Switch",
    },
    onLabel: {
      type: String,
      default: "On",
    },
    offLabel: {
      type: String,
      default: "Off",
    },
    value: {
      type: Boolean,
      required: true,
      default: false,
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: String || Array,
      default: "",
    },
    onChange: {
      type: Function,
      default: (event) => {},
    },
    tooltip: {
      type: String,
      default: null,
    },
    status: {
      type: Boolean,
      default: false,
    },
  },
  methods: {
    handleInput(val) {
      this.$emit("input", val);
    },
  },
};
</script>
