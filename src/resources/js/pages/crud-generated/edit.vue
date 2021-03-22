<template>
  <div>
    <div v-if="globalComponentList[$route.params.slug + '-edit']">
      <component
        v-bind:is="globalComponentList[$route.params.slug + '-edit']"
      ></component>
    </div>
    <div v-else>
      <crud-template-edit />
    </div>
  </div>
</template>

<script>
import CrudTemplateEdit from "../crud-template/edit";
export default {
  components: {
    CrudTemplateEdit,
  },
  name: "EditEntity",
  data: () => ({
    globalComponentList: {},
  }),
  mounted() {
    this.globalComponentList = this.constructor.superOptions.components;
  },
  methods: {},
};
</script>
