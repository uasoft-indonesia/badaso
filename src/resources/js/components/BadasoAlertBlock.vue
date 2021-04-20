<template>
  <div class="tip custom-block">
    <p class="custom-block-title">
      <slot name="title"></slot>
    </p>
    <p>
      <slot name="desc"></slot>
    </p>
  </div>
</template>
<script>
export default {
  name: "BadasoAlertBlock",
  components: {},
}
</script>
<style scoped>
.custom-block.tip {
  background-color: #f3f5f7;
  border-color: var(--danger);
  border-left-width: .2rem;
  border-left-style: solid;
  margin: -15px -15px 0 -15px;
}

.custom-block .custom-block-title {
  font-weight: bold;
  margin-bottom: -.4rem;
}

.custom-block p {
  width: 100%;
  padding: 10px 20px;
  background: transparent!important;
  margin-top: 0;
  border-radius: 10px;
}
</style>