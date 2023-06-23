<template>
  <div class="badaso-alert__body">
    <p class="badaso-alert__title">
      <slot name="title" />
    </p>
    <p class="badaso-alert__description">
      <slot name="desc" />
    </p>
  </div>
</template>
<script>
export default {
  name: "BadasoAlertBlock",
  components: {},
};
</script>
