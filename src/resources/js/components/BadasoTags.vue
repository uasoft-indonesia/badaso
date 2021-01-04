<template>
  <vs-col :vs-lg="size" class="mb-3">
    <label for="" class="vs-input--label">{{ label }}</label>
    <vue-tags-input
      v-model="tag"
      :tags="tags"
      @tags-changed="handleInput"
    />
  </vs-col>
</template>

<script>
import VueTagsInput from '@johmun/vue-tags-input';
import _ from 'lodash'

export default {
  name: "BadasoTags",
  components: {
    VueTagsInput
  },
  data: () => ({
      tag: "",
      tags: []
  }),
  props: {
    size: {
      type: String,
      default: "12",
    },
    label: {
      type: String,
      default: "Tags",
    },
    placeholder: {
      type: String,
      default: "Tags",
    },
    value: {
      type: String,
      required: true,
    },
  },
  beforeMount() {
    this.tags = this.value.split(',')
  },
  methods: {
    handleInput(newTags) {
      this.tags = newTags
      let tags = _.map(this.tags, 'text')
      tags = tags.join()
      this.$emit("input", tags);
    },
  },
};
</script>

<style>
  .vue-tags-input {
    max-width: unset !important;
  }
</style>