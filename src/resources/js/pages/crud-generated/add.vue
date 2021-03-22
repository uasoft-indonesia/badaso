<template>
  <div>
    <div v-if="globalComponentList[$route.params.slug + '-add']">
      <component
        v-bind:is="globalComponentList[$route.params.slug + '-add']"
      ></component>
    </div>
    <div v-else>
      <crud-template-add />
    </div>
  </div>
</template>

<script>
import CrudTemplateAdd from "../crud-template/add";
export default {
  components: {
    CrudTemplateAdd,
  },
  name: "AddEntity",
  data: () => ({
    globalComponentList: {},
  }),
  mounted() {
    this.globalComponentList = this.constructor.superOptions.components;
  },
  methods: {},
};
</script>
