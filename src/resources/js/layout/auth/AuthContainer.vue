<template>
  <div class="blank_layout">
    <vs-row class="full-height login-register-bg" style="align-items: center; justify-content: center; display: flex; width: 100%;">
      <router-view></router-view>
    </vs-row>
    <badaso-licence-blocker />
  </div>
</template>

<script>

export default {
  name: "AuthContainer",
  components: {
  }
};
</script>

<style>
.login-register-bg {
    margin: 0 auto;
}
</style>