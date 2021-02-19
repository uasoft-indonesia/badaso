<template>
  <div
    :class="{'vs-sidebar-group-open' : openItems}"
    class="vs-sidebar-group"
    @mouseover="mouseover"
    @mouseout="mouseout">
    <div style="display: flex">
    <vs-icon style="padding-top: 10px; font-size: 1rem; margin-left: 18px;"
        v-if="icon"
        :icon-pack="iconPack"
        :icon="icon">
    </vs-icon>
    <h4 @click="clickGroup" v-if="icon"  style="text-align: left;" class="hide-in-minisidebar">
    <vs-icon style="font-size: 1rem;">keyboard_arrow_down</vs-icon>
        {{ title }} </br>
        {{ subTitle }} 
    </h4>
    <h4 @click="clickGroup" v-else style="text-align: left;">
    <vs-icon  style="font-size: 1rem;">keyboard_arrow_down</vs-icon>
        {{ title }} 
        </br>
        {{ subTitle }} 
    </h4>
    <span class="vs-sidebar--tooltip">{{ title }}</span>
    </div>
    <ul
      ref="items"
      :style="styleItems"
      class="vs-sidebar--group-items">
      <slot></slot>
    </ul>
  </div>
</template>
<script>
export default {
  name:'BadasoSidebarGroup',
  props: {
      icon: {
      default: null,
      type: String
    },
    iconPack: {
      default: 'material-icons',
      type: String
    },
    collapsed: {
      default: false,
      type: Boolean
    },
    title: {
      default: null,
      type: String
    },
    subTitle: {
      default: null,
      type: String
    },
    openHover: {
      default: false,
      type: Boolean
    },
    open: {
      default: false,
      type: Boolean
    }
  },
  data: () => ({
    maxHeight: '0px',
    openItems: false
  }),
  computed:{
    styleItems() {
      return {
        maxHeight: this.maxHeight,
    }
    }
  },
  watch: {
    maxHeight () {
      this.openItems = this.maxHeight != '0px'

    }
  },
  mounted () {
    this.openItems = this.open
    if(this.open) {
      this.maxHeight = 'none'
    }
  },
  methods:{
    getActive () {
      return this.$parent.getActive()
    },
    setIndexActive (index) {
      this.$parent.setIndexActive(index)
    },
    clickGroup() {
      if(!this.openHover) {
        let scrollHeight = this.$refs.items.scrollHeight
        if(this.maxHeight == '0px') {
          this.maxHeight = `${scrollHeight}px`
          setTimeout(() => {
            this.maxHeight = 'none'
          },300)
        } else {
          this.maxHeight = `${scrollHeight}px`
          setTimeout(() => {
            this.maxHeight = `${0}px`
          }, 50)
        }
      }
    },
    mouseover() {
      if(this.openHover) {
        let scrollHeight = this.$refs.items.scrollHeight
        this.maxHeight = `${scrollHeight}px`
      }
    },
    mouseout() {
      if(this.openHover) {
        let scrollHeight = 0
        this.maxHeight = `${scrollHeight}px`
      }
    }
  }
}
</script>