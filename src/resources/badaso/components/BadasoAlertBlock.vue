<template>
  <div class="badaso-alert__body">
    <p class="badaso-alert__title">
      <slot name="title"></slot>
    </p>
    <p class="badaso-alert__description">
      <slot name="desc"></slot>
    </p>
  </div>
</template>
<script>
export default {
  name: "BadasoAlertBlock",
  components: {},
};
</script>
