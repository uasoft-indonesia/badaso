<template lang="html">

   <div id="parentx">
    <vs-sidebar default-index="1" :parent="parent" :hiddenBackground="doNotClose" color="primary" class="sidebarx" spacer v-model="isSidebarActive" :click-not-close="doNotClose" >
        <div class="header-sidebar text-center" slot="header">
            <vs-avatar size="70px" src="https://randomuser.me/api/portraits/men/85.jpg"/>
            <h4>Steave Jobs<br/>
              <small>varun@gmail.com</small>
            </h4>
        </div>
        <template v-for="(sidebarLink, index) in sidebarLinks" >
            <vs-sidebar-item :icon="sidebarLink.icon" :to="sidebarLink.url" :key="`sidebarLink-${index}`" :index="index">
              <span class="hide-in-minisidebar">{{ sidebarLink.name }}</span>
            </vs-sidebar-item>
        </template>  
        
        <div class="footer-sidebar" slot="footer">
            <vs-button icon="reply" color="danger" type="flat" href="https://www.wrappixel.com">Upgrade to Pro</vs-button>
        </div>

    </vs-sidebar>
  </div>


</template>

<script>

export default {
  name: "SideBar",
  props: {
    parent: {
        type: String
    },
    sidebarLinks: {
      type: Array,
      required: true,
    },
    index: {
        default: null,
        type: [String, Number]
    }
  },
  data:()=>({
    doNotClose: false,
    windowWidth: window.innerWidth,
  }),
  computed: {
      //This is for mobile trigger
      isSidebarActive: {
        get() {
          return this.$store.state.isSidebarActive
        },
        set(val) {
          this.$store.commit('IS_SIDEBAR_ACTIVE', val)
        }
      }
   },
   watch: {
      
   },   
   methods : {
      handleWindowResize(event) {
            this.windowWidth = event.currentTarget.innerWidth;
            this.setSidebar();
        },
      setSidebar() {
        if (this.windowWidth < 1170) {
          this.$store.commit('IS_SIDEBAR_ACTIVE', false);
          this.doNotClose= false
        } 
        else {
          this.$store.commit('IS_SIDEBAR_ACTIVE', true);
          this.doNotClose= true 
        }
      }
  },
  mounted() {
      this.$nextTick(() => {
            window.addEventListener('resize', this.handleWindowResize);
        });
      this.setSidebar();
    },
  beforeDestroy() {
    window.removeEventListener('resize', this.handleWindowResize);
    this.setSidebar();
  }
}
</script>