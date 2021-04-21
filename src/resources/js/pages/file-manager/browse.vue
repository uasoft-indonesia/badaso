<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        
      </template>
    </badaso-breadcrumb-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3> {{$t("fileManager.title")}} </h3>
          </div>
          <div>
            <iframe :src="pageIframe" style="width: 100%; height: 700px; overflow: hidden; border: none;"></iframe>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>

export default {
  name: "FileManagerBrowse",
  components: {},
  data: () => ({
    
  }),
  mounted() {
  },
  watch: {
    
  },
  methods: {
    
  },
  computed : {
    pageIframe : () => {
      let host = window.location.origin
      let token = localStorage.getItem('token')
      return `${host}/filemanager?token=${token}`
    }
  }
};
</script>
