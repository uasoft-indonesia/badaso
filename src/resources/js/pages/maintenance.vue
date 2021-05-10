<template>
  <div class="flex flex-direction-column justify-content-center align-items-center h-100vh" >
    <img src="/badaso-images/maintenance.png" alt="Maintenance Icon">

    <h1 class="mt-4 text-center">We are under <br>maintenance</h1>
  </div>
</template>

<script>
export default {
  name: "Maintenance",
};
</script>

<style lang="scss" scoped>
.flex {
  display: flex;
}

.justify-content-center {
  justify-content: center;
}

.flex-direction-column {
  flex-direction: column;
}

.align-items-center {
  align-items: center;
}

.h-100vh {
  height: 100vh;
}
</style>