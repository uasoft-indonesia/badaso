<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-switch__container">
    <div v-if="label === 'Maintenance for all pages.' && status">
      <label v-if="label !== ''" for="" class="badaso-switch__label">
        {{ label }}
        <span style="color: red;">
          ({{ $t("site.maintenanceMode") }}
          <a href="https://badaso-docs.uatech.co.id/getting-started/configuration#maintenance">
            {{ $t("database.browse.fieldNotSupport.button.visitDocs") }}
          </a>)
        </span>
        <vs-tooltip :text="tooltip" v-if="tooltip">
          <vs-icon icon="help_outline" size="16px" color="#A5A5A5"></vs-icon>
        </vs-tooltip>
      </label>
    </div>
    <div v-else>
      <label v-if="label !== ''" for="" class="badaso-switch__label">
        {{ label }}
        <vs-tooltip :text="tooltip" v-if="tooltip">
          <vs-icon icon="help_outline" size="16px" color="#A5A5A5"></vs-icon>
        </vs-tooltip>
      </label>
    </div>
    <vs-switch
      :modelValue="modelValue"
      :disabled="status === 'true'"
      @update:modelValue="handleInput"
    >
      <template #on>
        <span>{{ onLabel }}</span>
      </template>
      <template #off>
        <span>{{ offLabel }}</span>
      </template>
    </vs-switch>
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span class="badaso-switch__input--error" v-for="(info, index) in alert" :key="index">
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
    modelValue: {
      type: Boolean,
      required: true,
      default: false,
    },
    additionalInfo: {
      type: String,
      default: "",
    },
    alert: {
      type: [String, Array],
      default: "",
    },
    tooltip: {
      type: String,
      default: null,
    },
    status: {
      type: Boolean,
      default: false,
    }
  },
  methods: {
    handleInput(val) {
      this.$emit("update:modelValue", val);
    },
  },
};
</script>
