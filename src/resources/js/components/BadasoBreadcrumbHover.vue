<template>
  <vs-row class="badaso-breadcrumb-hover__container">
    <vs-col vs-lg="6" vs-md="12" vs-sm="12">
      <vs-icon
        icon="arrow_back_ios"
        class="badaso-breadcrumb-hover__icon"
        @click="goBack"
      />
      <vs-breadcrumb class="badaso-breadcrumb-hover__list">
        <li v-for="(item, index) in items" :key="index">
          <router-link :to="item.url">{{ item.title }}</router-link>
          <span class="badaso-breadcrumb-hover__separator">/</span>
        </li>
        <li aria-current="page">{{ activePage.title }}</li>
      </vs-breadcrumb>
    </vs-col>
    <vs-col vs-lg="6" vs-md="12" vs-sm="12">
      <div class="badaso-breadcrumb-hover__container--right">
        <badaso-dropdown>
          <vs-button type="relief" v-if="visibleButtonAction">Action</vs-button>
          <vs-dropdown-menu>
            <slot name="action" />
          </vs-dropdown-menu>
        </badaso-dropdown>
      </div>
    </vs-col>
  </vs-row>
</template>

<script>
export default {
  name: 'BadasoBreadcrumbRow',
  props: {
    full: {
      type: Boolean,
      default: false,
    },
    visibleButtonAction: {
      type: Boolean,
      default: true,
    },
  },
  data() {
    return {
      items: [],
      activePage: { title: '', url: '' },
    };
  },
  mounted() {
    let path = this.$route.path;
    if (!this.full) {
      const params = this.$route.params;
      Object.values(params).forEach(param => {
        path = path.replace(`/${param}`, '');
      });
    }
    const parts = path.split('/').filter(Boolean); // Menghapus bagian kosong
    let url = '';

    parts.forEach((part, index) => {
      url += `/${part}`;
      if (index === parts.length - 1) {
        this.activePage = {
          title: this.$helper.generateDisplayName(part),
          url,
        };
      } else {
        this.items.push({
          title: this.$helper.generateDisplayName(part),
          url,
        });
      }
    });
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
