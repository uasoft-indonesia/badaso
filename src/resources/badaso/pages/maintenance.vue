<template>
  <div
    class="badaso-maintenance__container--full badaso-maintenance__container"
  >
    <img :src="`${maintenanceImg}`" alt="Maintenance Icon" />
    <h1 class="badaso-maintenance__text">We are under <br />maintenance</h1>
  </div>
</template>

<script>
export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Maintenance",
  computed: {
    maintenanceImg() {
      const config = this.$store.getters["badaso/getConfig"];
      return config.maintenanceImage;
    },
  },
};
</script>
