<template>
  <div>
    <vs-table v-model="selected" :data="data" stripe multiple>
      <template slot="header">
        <vs-row style="margin-bottom: 0;">
          <vs-col vs-lg="6" vs-md="6" vs-sm="6" vs-xs="12">
            <div style="display: flex; align-items: center; margin: 15px;">
              Show&nbsp;
              <vs-select class="selectExample" v-model="limit" width="100px">
                <vs-select-item
                  :key="index"
                  :value="row"
                  :text="row"
                  v-for="(row, index) in descriptionItems"
                />
              </vs-select>
              &nbsp;Entries
            </div>
          </vs-col>
          <vs-col vs-lg="6" vs-md="6" vs-sm="6" vs-xs="12">
            <div class="con-input-search vs-table--search" style="float: right;">
              <input
                type="text"
                class="input-search vs-table--search-input"
                v-on:keyup.enter="handleSearch"
              />
              <i class="vs-icon notranslate icon-scale material-icons null"
                >search</i
              >
            </div>
          </vs-col>
        </vs-row>
      </template>
      <template slot="thead"><slot name="thead"/></template>
      <slot name="tbody" />
    </vs-table>
    <div class="con-pagination-table vs-table--pagination">
      <vs-row class="mt-3" vs-justify="space-between" vs-type="flex" vs-w="12">
        <vs-col
          class="vs-pagination--mb"
          vs-type="flex"
          vs-justify="flex-start"
          vs-align="center"
          vs-lg="6"
          vs-md="12"
          vs-sm="12"
          vs-xs="12"
        >
          <span class="vs-pagination-desc">
            {{ descriptionTitle }}: {{ paginationData.from }} - {{ paginationData.to }}
          {{ descriptionConnector }} {{ paginationData.total }}
          </span>
        </vs-col>
        <vs-col
          class="vs-pagination--mb"
          vs-type="flex"
          vs-justify="flex-end"
          vs-align="center"
          vs-lg="6"
          vs-md="12"
          vs-sm="12"
          vs-xs="12"
        >
          <vs-pagination
            :total="totalItem"
            v-model="page"
            style="margin-bottom: 0;"
          ></vs-pagination>
        </vs-col>
      </vs-row>
    </div>
  </div>
</template>

<script>
export default {
  name: "BadasoServerSideTable",
  props: {
    paginationData: {},
    data: {
      type: Array,
      default: [],
    },
    description: {
      default: true,
      type: Boolean,
    },
    descriptionItems: {
      default: () => [10, 50, 100, 200],
      type: Array,
    },
    descriptionTitle: {
      default: "Registries",
      type: String,
    },
    descriptionConnector: {
      default: "of",
      type: String,
    },
    descriptionBody: {
      type: String,
    },
  },
  data: () => ({
    selected: [],
    limit: 10,
    page: 1,
    currentSortKey: null,
    currentSortType: null,
  }),
  computed: {
    totalItem() {
      return this.paginationData.total > 0
        ? Math.ceil(this.paginationData.total / this.limit)
        : 1;
    },
  },
  watch: {
    page: function(to, from) {
      this.$emit("changePage", to);
    },
    limit: function(to, from) {
      this.$emit("changeLimit", to);
    },
    selected: function(to, from) {
      this.$emit("select", to)
    }
  },
  mounted() {},
  destroyed() {},
  methods: {
    handleSearch(e) {
      this.$emit("search", e);
    },
    handleSort(key, sortType) {
      this.currentSortKey = key;
      this.currentSortType = sortType;
      this.$emit('sort', key, sortType)
    },
  },
};
</script>
