<template>
  <vs-row class="badaso-breadcrumb-row__container">
    <vs-col vs-lg="6" vs-md="12" vs-sm="12">
      <vs-icon
        icon="arrow_back_ios"
        class="badaso-breadcrumb-row__icon"
        @click="goBack()"
      />
      <vs-breadcrumb>
        <li v-for="(item, index) in items" :key="index">
          <router-link :to="item.url">{{ item.title }}</router-link>
          <span class="badaso-breadcrumb-row__separator">/</span>
        </li>
        <li aria-current="page">{{ activePage.title }}</li>
      </vs-breadcrumb>
    </vs-col>
    <vs-col vs-lg="6" vs-md="12" vs-sm="12">
      <div class="badaso-breadcrumb-row__container--right">
        <slot name="action" />
      </div>
    </vs-col>
  </vs-row>
</template>

<script>
// eslint-disable-next-line no-unused-vars
import _ from "lodash";

export default {
  name: "BadasoBreadcrumbRow",
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
      const params = this.$route.params;
      const keys = Object.keys(params);
      for (let i = 0; i < keys.length; i++) {
        path = path.replace("/" + params[keys[i]], "");
      }
    }
    const part = path.split("/");
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
        this.$router.go(-1);
      }
    },
  },
};
</script>
