<template>
  <div class="badaso-maintenance__container--full badaso-maintenance__container" >
    <img :src="`${$store.state.badaso.meta.mediaBaseUrl}files/shares/maintenance.png`" alt="Maintenance Icon">
    <h1 class="badaso-maintenance__text">We are under <br>maintenance</h1>
  </div>
</template>

<script>
export default {
  name: "Maintenance",
};
</script>
