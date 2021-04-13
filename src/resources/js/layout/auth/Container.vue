<template>
  <div class="blank_layout">
    <vs-row class="full-height login-register-bg">
      <router-view :key="$route.path"></router-view>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "AuthContainer",
  components: {},
};
</script>
