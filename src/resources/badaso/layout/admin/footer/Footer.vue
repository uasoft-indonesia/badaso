<template>
  <div class="badaso-footer__container">
    <div class="badaso-footer__version">Version {{ version }}</div>
    <div>
      Copyright © 2020
      <a href="https://soft.uatech.co.id" target="_blank">UASOFT</a>. All rights
      reserved.
    </div>
  </div>
</template>

<script>
import composer from "../../../../../../composer.json";

export default {
  // eslint-disable-next-line vue/multi-word-component-names
  name: "Footer",
  computed: {
    version() {
      return composer.version;
    },
  },
};
</script>
