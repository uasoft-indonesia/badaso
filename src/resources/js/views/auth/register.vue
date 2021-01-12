<template>
  <vs-col
    vs-lg="12"
    class="login-register-box"
    style="justify-content: center; align-items: center; margin-left: 0%; width: 100%;"
  >
    <vs-card class="mb-0">
      <div slot="header">
        <h3 class="mb-1">Register Here</h3>
        <p class="mb-0">Welcome back, please login to your account.</p>
      </div>
      <div>
        <form novalidate="novalidate">
          <vs-input icon="person" icon-after size="default" placeholder="Name" v-model="name" class="w-100 mb-4 mt-2 "/>
          <vs-input icon="email" icon-after size="default" placeholder="Email" v-model="email" class="w-100 mb-4 mt-2 "/>
          <vs-input icon="lock" type="password" icon-after size="default" placeholder="Password" v-model="password" class="w-100 mb-4 mt-2 "/>
          <vs-input icon="lock" type="password" icon-after size="default" placeholder="Password Confirmation" v-model="passwordConfirmation" class="w-100 mb-4 mt-2 "/>
          <vs-button type="relief" class="btn-block">Register</vs-button>
        </form>

        <div class="d-flex justify-content-center mt-3">
          Do you have an account? &nbsp;
          <router-link :to="'/'+baseUrl+'/login'">login</router-link>
        </div>
      </div>
    </vs-card>
  </vs-col>
</template>

<script>
export default {
    data: () => ({
        name: '',
        email: '',
        password: '',
        passwordConfirmation: '',
        baseUrl: process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  ? process.env.MIX_ADMIN_PANEL_ROUTE_PREFIX
  : "badaso-admin"
    })
}
</script>

<style>
.login-register-box {
  max-width: 400px;
}
</style>
