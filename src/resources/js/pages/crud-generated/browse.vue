<template>
  <div>
    <div v-if="globalComponentList[$route.params.slug + '-browse']">
      <component
        v-bind:is="globalComponentList[$route.params.slug + '-browse']"
      ></component>
    </div>
    <div v-else>
      <crud-template-browse />
    </div>
  </div>
</template>

<script>
import CrudTemplateBrowse from "../crud-template/browse";
export default {
  components: {
    CrudTemplateBrowse,
  },
  name: "BrowseEntity",
  data: () => ({
    globalComponentList: {},
  }),
  mounted() {
    this.globalComponentList = this.constructor.superOptions.components;
  },
  methods: {},
};
</script>
