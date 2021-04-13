<template>
  <vs-row style="padding-bottom: 0;">
    <vs-col vs-lg="6" style="padding: 0;"
      ><h3 class="mb-1"><slot /></h3
    ></vs-col>
    <vs-col vs-lg="6" style="padding: 0;"
      ><badaso-logo-display></badaso-logo-display
    ></vs-col>
  </vs-row>
</template>

<script>
export default {
  name: "BadasoAuthCardHeader",
  components: {},
  data: () => ({}),
  computed: {},
};
</script>
