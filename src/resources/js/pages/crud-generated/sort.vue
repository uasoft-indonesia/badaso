<template>
  <div>
    <div v-if="globalComponentList[$route.params.slug + '-sort']">
      <component
        v-bind:is="globalComponentList[$route.params.slug + '-sort']"
      ></component>
    </div>
    <div v-else>
      <crud-template-sort />
    </div>
  </div>
</template>

<script>
import CrudTemplateSort from "../crud-template/sort";
export default {
  components: {
    CrudTemplateSort,
  },
  name: "SortEntity",
  data: () => ({
    globalComponentList: {},
  }),
  mounted() {
    this.globalComponentList = this.constructor.superOptions.components;
  },
  methods: {},
};
</script>
