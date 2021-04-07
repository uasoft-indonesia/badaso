<template>
  <div class="blank_layout">
    <div class="full-height login-register-bg">
      <vs-row style="justify-content: center;">
        <vs-col vs-lg="12" v-if="adminPanelLogo" style="text-align: center; margin-bottom: 20px;">
          <img :src="adminPanelLogo" alt="adminPanelTitle" style="max-width: 300px;" />
        </vs-col>
        <router-view  :key="$route.path"></router-view>
      </vs-row>
    </div>
  </div>
</template>

<script>

export default {
  name: "AuthContainer",
  components: {
  },
  computed: {
    adminPanelTitle:{
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelTitle ?  config.adminPanelTitle : 'Badaso'
      }
    },
    adminPanelLogo:{
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  this.$api.badasoFile.view(config.adminPanelLogo)
      }
    },
    adminPanelHeaderColor: {
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelHeaderColor ?  config.adminPanelHeaderColor : "#fff"
      }
    },
    adminPanelLogoConfig: {
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelLogoConfig ?  config.adminPanelLogoConfig : "logo_and_text"
      }
    },
    adminPanelHeaderFontColor: {
      get() {
        let config = this.$store.getters['badaso/getConfig']
        return  config.adminPanelHeaderFontColor ?  config.adminPanelHeaderFontColor : "#000"
      }
    },
  },
};
</script>