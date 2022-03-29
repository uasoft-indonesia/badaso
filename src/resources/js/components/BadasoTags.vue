<template>
  <vs-col :vs-lg="size" vs-xs="12" class="badaso-tags__container">
    <label v-if="label != ''" for="" class="badaso-tags__label">{{
      label
    }}</label>
    <vue-tags-input
      class="badaso-tags__input"
      v-model="tag"
      :tags="tags"
      @tags-changed="handleInput"
    />
    <div v-if="additionalInfo" v-html="additionalInfo"></div>
    <div v-if="alert">
      <div v-if="$helper.isArray(alert)">
        <span
          class="badaso-tags__input--error"
          v-for="(info, index) in alert"
          :key="index"
        >
          {{ info }}
        </span>
      </div>
      <div v-else>
        <span class="badaso-tags__input--error" v-html="alert"></span>
      </div>
    </div>
  </vs-col>
</template>

<script>
import VueTagsInput from "@johmun/vue-tags-input";
import _ from "lodash";

export default {
  name: "BadasoTags",
  components: {
    VueTagsInput,
  },
  data: () => ({
    tag: "",
    tags: [],
  }),
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
      default: "Tags",
    },
    value: {
      type: String,
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
  beforeMount() {
    if (this.value && this.value != "") {
      this.tags = this.value.split(",");
    }
  },
  methods: {
    handleInput(newTags) {
      this.tags = newTags;
      let tags = _.map(this.tags, "text");
      tags = tags.join();
      this.$emit("input", tags);
    },
  },
};
</script>
