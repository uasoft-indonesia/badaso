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
            <Tree
              :data="menus"
              draggable="draggable"
              cross-tree="cross-tree"
              class="menu-management__tree"
              :ondragend="saveMenuOrder()"
            >
              <vs-row
                vs-w="12"
                vs-justify="space-between"
                slot-scope="{ data }"
              >
                <vs-col
                  vs-type="flex"
                  vs-justify="flex-start"
                  vs-align="center"
                  vs-w="2"
                >
                  <strong>{{ data.displayName }}</strong>
                </vs-col>
                <vs-col
                  vs-type="flex"
                  vs-justify="flex-start"
                  vs-align="center"
                  vs-w="2"
                >
                  <vs-checkbox
                    :value="data.isShowHeader"
                    @change="saveCheckMenuShowHeader(data.id)"
                    >{{ $t("menu.options.showHeader") }}</vs-checkbox
                  >
                </vs-col>
                <vs-col
                  vs-type="flex"
                  vs-justify="flex-start"
                  vs-align="center"
                  vs-w="2"
                >
                  <vs-checkbox
                    :value="data.isExpand"
                    @change="saveCheckMenuExpand(data)"
                    >{{ $t("menu.options.expand") }}</vs-checkbox
                  >
                </vs-col>
                <vs-col
                  vs-type="flex"
                  vs-justify="flex-end"
                  vs-align="center"
                  vs-w="2"
                >
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
                          params: { id: data.id },
                        }"
                      >
                        Manage Items
                      </badaso-dropdown-item>
                      <badaso-dropdown-item
                        icon="edit"
                        v-if="$helper.isAllowed('edit_menus')"
                        :to="{
                          name: 'MenuManagementEdit',
                          params: { id: data.id },
                        }"
                      >
                        Edit
                      </badaso-dropdown-item>
                      <badaso-dropdown-item
                        icon="delete"
                        v-if="$helper.isAllowed('delete_menus')"
                        @click="openConfirm(data.id)"
                      >
                        Delete
                      </badaso-dropdown-item>
                    </vs-dropdown-menu>
                  </badaso-dropdown>
                </vs-col>
              </vs-row>
            </Tree>
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
import { DraggableTree } from "vue-draggable-nested-tree";
// eslint-disable-next-line no-unused-vars
import _ from "lodash";

export default {
  components: {
    Tree: DraggableTree,
  },
  name: "MenuManagementBrowse",
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    menus: [],
    saveOrder: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getMenuList();
  },
  methods: {
    saveMenuOrder() {
      const order = this.menus
        .map((item) => item.id)
        .filter((item) => item != undefined);
      if (JSON.stringify(order) != JSON.stringify(this.saveOrder)) {
        this.saveOrder = order;
        this.$api.badasoMenu
          .menuOptions({ order })
          .then((res) => {
            this.$store.commit("badaso/FETCH_MENU");
          })
          .catch((error) => {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          });
      }
    },
    saveCheckMenuExpand(menu) {
      const { id, isExpand } = menu;

      this.$api.badasoMenu
        .menuOptions({ menu_id: id, is_expand: !isExpand, type: "menu" })
        .then((res) => {
          this.$store.commit("badaso/FETCH_MENU");
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    saveCheckMenuShowHeader(menuId) {
      this.$api.badasoMenu
        .menuOptions({ menu_id: menuId, is_show_header: "event" })
        .then((res) => {
          this.$store.commit("badaso/FETCH_MENU");
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
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
