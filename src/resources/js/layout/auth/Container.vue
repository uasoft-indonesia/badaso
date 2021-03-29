<template>
  <div class="blank_layout">
    <vs-row class="full-height login-register-bg">
      <router-view  :key="$route.path"></router-view>
    </vs-row>
    <badaso-license-blocker />
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
    background: url('/badaso-images/auth-bg.jpg');
    background-size: cover;
    align-items: center;
    justify-content: center;
    display: flex;
    width: 100%;
}
</style>