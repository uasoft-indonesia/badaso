<template>
    <!-- <vs-breadcrumb :items="items"></vs-breadcrumb> -->
    <vs-breadcrumb>
      <li v-for="(item, index) in items" :key="index">
        <router-link
              :to="item.url"
              >{{item.title}}</router-link
            >
        <span class="vs-breadcrum--separator">/</span>
      </li>
      <li aria-current="page" class="active">{{activePage.title}}</li>
    </vs-breadcrumb>
</template>

<script>
import _ from 'lodash'

export default {
  name: "BadasoText",
  components: {},
  data: () => ({
    items: [],
    activePage: {}
  }),
  props: {},
  computed: {},
  mounted() {
    let params = this.$route.params
    let keys = Object.keys(params);
    let path = this.$route.path
    for (let i = 0; i < keys.length; i++) {
      path = path.replace('/'+params[keys[i]], "")
    }
    let part = path.split('/')
    let url = ""
    for (let index = 1; index < part.length; index++) {
      url = url + "/" + part[index]
      if (index == part.length - 1) {
        this.activePage = {
          title: this.$helper.generateDisplayName(part[index]),
          url: url
        }
      } else {
        this.items.push({
          title: this.$helper.generateDisplayName(part[index]),
          url: url
        })
      }
    }
  },
  methods: {
  },
};
</script>