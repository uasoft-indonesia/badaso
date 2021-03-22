<template>
  <div>
    <div v-if="globalComponentList[$route.params.slug + '-read']">
      <component
        v-bind:is="globalComponentList[$route.params.slug + '-read']"
      ></component>
    </div>
    <div v-else>
      <crud-template-read />
    </div>
  </div>
</template>

<script>
import CrudTemplateRead from "../crud-template/read";
export default {
  components: {
    CrudTemplateRead,
  },
  name: "ReadEntity",
  data: () => ({
    globalComponentList: {},
  }),
  mounted() {
    this.globalComponentList = this.constructor.superOptions.components;
  },
  methods: {},
};
</script>
