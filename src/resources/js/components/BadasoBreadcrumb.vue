<template>
  <div>
    <vs-icon icon="arrow_back_ios" style="cursor:pointer; float:left; padding-top: 0.6rem;" @click="goBack()"></vs-icon>
    <vs-breadcrumb>
      <li v-for="(item, index) in items" :key="index">
        <router-link :to="item.url">{{ item.title }}</router-link>
        <span class="vs-breadcrum--separator">/</span>
      </li>
      <li aria-current="page" class="active">{{ activePage.title }}</li>
    </vs-breadcrumb>
  </div>
</template>

<script>
import _ from "lodash";

export default {
  name: "BadasoBreadcrumb",
  components: {},
  data: () => ({
    items: [],
    activePage: {},
  }),
  props: {
    full: {
      type: Boolean,
      default: false,
    },
  },
  computed: {},
  mounted() {
    let path = this.$route.path;
    if (!this.full) {
      let params = this.$route.params;
      let keys = Object.keys(params);
      for (let i = 0; i < keys.length; i++) {
        path = path.replace("/" + params[keys[i]], "");
      }
    }
    let part = path.split("/");
    let url = "";
    for (let index = 1; index < part.length; index++) {
      url = url + "/" + part[index];
      if (index == part.length - 1) {
        this.activePage = {
          title: this.$helper.generateDisplayName(part[index]),
          url: url,
        };
      } else {
        this.items.push({
          title: this.$helper.generateDisplayName(part[index]),
          url: url,
        });
      }
    }
  },
  methods: {
    goBack() {
      if (window.history.length > 2) {
        this.$router.go(-1)
      }
    }
  },
};
</script>
