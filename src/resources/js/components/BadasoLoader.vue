<template>
  <vs-prompt :active.sync="loader" buttons-hidden :title="message" type="confirm">
    <vs-progress indeterminate color="primary">primary</vs-progress>
  </vs-prompt>
</template>

<script>
export default {
  name: "BadasoLoader",
  data() {
    return {
      loader: false,
      message: "Loading",
    };
  },
  methods: {
    openLoader(payload = null) {
      this.message = payload ? payload.message : "Loading";
      this.loader = true;
    },
    closeLoader() {
      this.loader = false;
    },
  },
};
</script>
