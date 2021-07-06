<template>
  <div class="badaso-footer__container">
    <div style="badaso-footer__version">Version {{version}}</div>
    <div>Copyright © 2020 <a href="https://soft.uatech.co.id" target="_blank">UASOFT</a>. All rights reserved.</div>
  </div>
</template>

<script>

import composer from '../../../../../../composer.json'

export default {
  computed : {
    version(){
      return composer.version
    }
  }
}
</script>
