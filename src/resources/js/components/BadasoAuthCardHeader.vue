<template>
  <vs-row class="badaso-auth-card-header__container">
    <vs-col vs-lg="6" class="badaso-auth-card-header__title">
      <h3 class="badaso-auth-card-header__title--text">
        <slot />
      </h3>
    </vs-col>
    <vs-col vs-lg="6" class="badaso-auth-card-header__logo">
      <badaso-logo-display />
    </vs-col>
  </vs-row>
</template>

<script>
export default {
  name: "BadasoAuthCardHeader",
  components: {},
  data: () => ({}),
  computed: {},
};
</script>
