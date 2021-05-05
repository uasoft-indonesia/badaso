<template>
  <vs-button
    :to="{
      name: 'DataPendingEditRead',
      params: {
        urlBase64: base64PathName,
      },
    }"
    v-if="dataLength > 0"
    color="success"
    type="relief"
  >
    <vs-icon icon="history"></vs-icon>
    <strong>{{$t('offlineFeature.dataUpdatePending')}}</strong>
  </vs-button>
</template>
<script>
import { readObjectStore } from "../utils/indexed-db";

export default {
  name: "BadasoButtonDataPendingUpdate",
  components: {},
  data() {
    return {
      dataLength: 0,
      pathname: location.pathname,
    };
  },
  methods: {
    requestObjectStoreData() {
      readObjectStore(this.pathname).then((store) => {
        if (store.result) {
          this.dataLength = store.result.data.length;
        }
      });
    },
  },
  computed: {
    base64PathName() {
      return window.btoa(location.pathname);
    },
  },
  mounted() {
    this.requestObjectStoreData();
  },
};
</script>
