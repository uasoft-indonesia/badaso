<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'MenuManagementAdd' }"
          ><vs-icon icon="add"></vs-icon> {{ $t("action.add") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("menu.title") }}</h3>
          </div>
          <div>
            <badaso-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="menus"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('menu.footer.descriptionTitle')"
              :description-connector="$t('menu.footer.descriptionConnector')"
              :description-body="$t('menu.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="key"> {{ $t("menu.header.key") }} </vs-th>
                <vs-th sort-key="displayName">
                  {{ $t("menu.header.displayName") }}
                </vs-th>
                <vs-th>{{ $t("menu.header.action") }}</vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  :data="table"
                  :key="index"
                  v-for="(table, index) in data"
                >
                  <vs-td :data="data[index].key">
                    {{ data[index].key }}
                  </vs-td>
                  <vs-td :data="data[index].displayName">
                    {{ data[index].displayName }}
                  </vs-td>
                  <vs-td class="badaso-table__td">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="list"
                          v-if="$helper.isAllowed('edit_menus')"
                          :to="{
                            name: 'MenuManagementBuilder',
                            params: { id: data[index].id },
                          }"
                        >
                          Manage Items
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="edit"
                          v-if="$helper.isAllowed('edit_menus')"
                          :to="{
                            name: 'MenuManagementEdit',
                            params: { id: data[index].id },
                          }"
                        >
                          Edit
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          v-if="$helper.isAllowed('delete_menus')"
                          @click="openConfirm(data[index].id)"
                        >
                          Delete
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </badaso-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("menu.warning.notAllowedToBrowse") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  components: {},
  name: "MenuManagementBrowse",
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    menus: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getMenuList();
  },
  methods: {
    openConfirm(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteMenu,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    getMenuList() {
      this.$openLoader();
      this.$api.badasoMenu
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.menus = response.data.menus;
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteMenu() {
      this.$openLoader();
      this.$api.badasoMenu
        .delete({
          menu_id: this.willDeleteId,
        })
        .then((response) => {
          this.$closeLoader();
          this.getMenuList();
          this.$store.commit("badaso/FETCH_MENU");
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
  },
};
</script>
