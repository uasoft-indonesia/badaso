<template>
  <div>
    <vs-popup
      :title="$t('menu.builder.popup.add.title')"
      :active.sync="addMenuItemPopUp"
    >
      <vs-row>
        <badaso-text
          v-model="menuItem.title"
          size="12"
          :label="$t('menu.builder.popup.add.field.title')"
          placeholder=""
          :alert="errors.title"
        ></badaso-text>
        <badaso-text
          v-model="menuItem.url"
          size="12"
          :label="$t('menu.builder.popup.add.field.url')"
          placeholder=""
          :alert="errors.url"
        ></badaso-text>
        <badaso-select
          v-model="menuItem.target"
          size="12"
          :label="$t('menu.builder.popup.add.field.target.title')"
          :items="menuItemTargets"
          placeholder=""
          :alert="errors.target"
        ></badaso-select>
        <badaso-text
          v-model="menuItem.iconClass"
          size="12"
          :label="$t('menu.builder.popup.add.field.icon.title')"
          placeholder=""
          :additionalInfo="$t('menu.builder.popup.add.field.icon.description')"
          :alert="errors.icon"
        ></badaso-text>
        <badaso-color-picker
          size="12"
          v-model="menuItem.color"
          :alert="errors.color"
        ></badaso-color-picker>
      </vs-row>
      <vs-row vs-type="flex" vs-justify="space-between">
        <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
          <vs-button
            class="menu-management__button"
            color="danger"
            @click="closeModal()"
            type="relief"
            >{{ $t("menu.builder.popup.add.button.cancel") }}</vs-button
          >
        </vs-col>
        <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
          <vs-button
            class="menu-management__button"
            color="primary"
            @click="saveMenuItem()"
            type="relief"
            >{{ $t("menu.builder.popup.add.button.add") }}</vs-button
          >
        </vs-col>
      </vs-row>
    </vs-popup>
    <badaso-breadcrumb-row>
      <template slot="action" v-if="$helper.isAllowed('add_menu_items')">
        <vs-button color="primary" type="relief" @click="addMenuItem()"
          ><vs-icon icon="add"></vs-icon> {{ $t("action.addItem") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("menu.builder.title") }}</h3>
          </div>
          <vs-row>
            <vs-col class="menu-management__builder-col">
              <Tree
                :data="menuItems"
                draggable="draggable"
                cross-tree="cross-tree"
                :ondragend="saveMenuOrder()"
                class="menu-management__tree"
              >
                <div
                  slot-scope="{ data, store }"
                  class="menu-management__box"
                  @click="store.toggleOpen(data)"
                >
                  <template v-if="!data.isDragPlaceHolder">
                    <div class="menu-management__display">
                      <div class="menu-management__text-display">
                        <b v-if="data.children && data.children.length"
                          >{{ data.open ? "-" : "+" }}&nbsp;
                        </b>
                        <b v-else> &nbsp;&nbsp; </b>
                        <span>{{ data.title }}</span>
                      </div>
                    </div>
                    <div class="align-content-center align-items-center">
                      <vs-checkbox
                        :value="data.isExpand"
                        @change="saveCheckMenuItemExpand(data)"
                        >{{ $t("menu.options.expand") }}</vs-checkbox
                      >
                    </div>
                    <div class="menu-management__action">
                      <badaso-dropdown vs-trigger-click>
                        <vs-button
                          size="large"
                          type="flat"
                          icon="more_vert"
                        ></vs-button>
                        <vs-dropdown-menu>
                          <badaso-dropdown-item
                            icon="list"
                            v-if="$helper.isAllowed('edit_menu_items')"
                            :to="{
                              name: 'MenuManagementPermissions',
                              params: { id: $route.params.id, itemId: data.id },
                            }"
                          >
                            Menu Permission
                          </badaso-dropdown-item>
                          <badaso-dropdown-item
                            icon="edit"
                            @click="editMenuItem(data)"
                            v-if="$helper.isAllowed('edit_menu_items')"
                          >
                            Edit
                          </badaso-dropdown-item>
                          <badaso-dropdown-item
                            icon="delete"
                            @click="openConfirm(data.id)"
                            v-if="$helper.isAllowed('delete_menu_items')"
                          >
                            Delete
                          </badaso-dropdown-item>
                        </vs-dropdown-menu>
                      </badaso-dropdown>
                    </div>
                  </template>
                </div>
              </Tree>
              <!-- popup modal edit -->
              <vs-popup
                :title="$t('menu.builder.popup.edit.title')"
                :active.sync="editMenuItemPopUp"
              >
                <vs-row>
                  <badaso-text
                    v-model="tempMenuItemDataEdit.title"
                    size="12"
                    :label="$t('menu.builder.popup.edit.field.title')"
                    placeholder=""
                    :alert="errors.tile"
                  ></badaso-text>
                  <badaso-text
                    v-model="tempMenuItemDataEdit.url"
                    size="12"
                    :label="$t('menu.builder.popup.edit.field.url')"
                    placeholder=""
                    :alert="errors.url"
                  ></badaso-text>

                  <badaso-select
                    v-model="tempMenuItemDataEdit.target"
                    size="12"
                    :label="$t('menu.builder.popup.edit.field.target.title')"
                    :items="menuItemTargets"
                    placeholder=""
                    :alert="errors.target"
                  ></badaso-select>

                  <badaso-text
                    v-model="tempMenuItemDataEdit.iconClass"
                    size="12"
                    :label="$t('menu.builder.popup.edit.field.icon.title')"
                    placeholder=""
                    :additionalInfo="
                      $t('menu.builder.popup.edit.field.icon.description')
                    "
                    :alert="errors.icon"
                  ></badaso-text>
                  <badaso-color-picker
                    size="12"
                    v-model="tempMenuItemDataEdit.color"
                    :alert="errors.color"
                  ></badaso-color-picker>
                </vs-row>
                <vs-row vs-type="flex" vs-justify="space-between">
                  <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                    <vs-button
                      class="menu-management__button"
                      color="danger"
                      @click="closeModal()"
                      type="relief"
                      >{{
                        $t("menu.builder.popup.edit.button.cancel")
                      }}</vs-button
                    >
                  </vs-col>
                  <vs-col vs-lg="2" vs-type="flex" vs-align="flex-end">
                    <vs-button
                      class="menu-management__button"
                      color="primary"
                      @click="updateMenuItem(tempMenuItemDataEdit)"
                      type="relief"
                      >{{
                        $t("menu.builder.popup.edit.button.edit")
                      }}</vs-button
                    >
                  </vs-col>
                </vs-row>
              </vs-popup>
              <!-- end popup modal -->
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("menu.warning.notAllowedToEdit") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import { DraggableTree } from "vue-draggable-nested-tree";
import _ from "lodash";

export default {
  name: "MenuManagementBuilder",
  components: {
    Tree: DraggableTree,
  },
  data: () => ({
    errors: {},
    fieldList: [],
    menuItems: [],
    menuItem: {
      color: "",
    },
    tempMenuItemDataEdit: {},
    savedItems: [],
    flatSavedItems: [],
    arrangeItems: false,
    addMenuItemPopUp: false,
    editMenuItemPopUp: false,
    menuItemIdWillBeDeleted: null,
    menuItemTargets: [
      {
        label: "This Tab",
        value: "_self",
      },
      {
        label: "New Tab",
        value: "_blank",
      },
    ],
  }),
  watch: {
    arrangeItems: function (val) {
      if (val) {
        this.startArrangeItems();
      }
    },
  },
  mounted() {
    this.menuItemTargets = [
      {
        label: this.$t("menu.builder.popup.add.field.target.value.thisTab"),
        value: "_self",
      },
      {
        label: this.$t("menu.builder.popup.add.field.target.value.newTab"),
        value: "_blank",
      },
    ];
    this.getMenuItems();
  },
  methods: {
    openConfirm(id) {
      this.menuItemIdWillBeDeleted = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteMenuItem,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    getMenuItems() {
      this.arrangeItems = false;
      this.$openLoader();
      this.$api.badasoMenu
        .browseItem({
          menuId: this.$route.params.id,
        })
        .then((response) => {
          this.menuItems = response.data.menuItems.map((item) => {
            const _item = item;
            _item.editItem = false;
            _item.title = item.title ? item.title : "";
            _item.iconClass = item.iconClass ? item.iconClass : "";
            _item.color = item.color ? item.color : "#000000";
            return _item;
          });
          this.savedItems = [...response.data.menuItems];
          this.flatSavedItems = this.flattenItems([...response.data.menuItems]);
          this.$closeLoader();
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
          this.$closeLoader();
        });
    },
    saveMenuOrder() {
      const isEqual = _.isEqual(this.menuItems, this.savedItems);
      if (!isEqual) {
        const isChange = this.checkArray(this.menuItems);
        if (isChange == true) {
          this.arrangeItems = true;
        }
      }
    },
    checkArray(items) {
      for (let i = 0; i < items.length; i++) {
        const idx = _.findIndex(this.flatSavedItems, (o) => {
          return o.id == items[i].id;
        });
        if (items[i].children && items[i].children.length > 0) {
          const stat = this.checkArray(items[i].children);
          if (!stat) {
            return false;
          }
        } else {
          if (idx == -1) {
            return false;
          }
        }
      }
      return true;
    },
    flattenItems(items) {
      let flatten = [];
      for (let i = 0; i < items.length; i++) {
        if (items[i].children && items[i].children.length > 0) {
          const flattenChildren = this.flattenItems(items[i].children);
          flatten.push(items[i]);
          flatten = flatten.concat(flattenChildren);
        } else {
          flatten.push(items[i]);
        }
      }
      return flatten;
    },
    buildArrangeItems(items) {
      const ls = [];
      for (let i = 0; i < items.length; i++) {
        if (items[i].children && items[i].children.length > 0) {
          const format = {
            id: items[i].id,
            menuId: items[i].menuId,
            title: items[i].title,
            url: items[i].url,
            target: items[i].target,
            iconClass: items[i].iconClass,
            color: items[i].color,
            parentId: items[i].parentId,
            order: items[i].order,
            permissions: items[i].permissions,
            children: this.buildArrangeItems(items[i].children),
          };
          ls.push(format);
        } else {
          const format = {
            id: items[i].id,
            menuId: items[i].menuId,
            title: items[i].title,
            url: items[i].url,
            target: items[i].target,
            iconClass: items[i].iconClass,
            color: items[i].color,
            parentId: items[i].parentId,
            order: items[i].order,
            permissions: items[i].permissions,
            children: [],
          };
          ls.push(format);
        }
      }

      return ls;
    },
    startArrangeItems() {
      this.$openLoader();
      const menuItems = this.buildArrangeItems(this.menuItems);
      this.$api.badasoMenu
        .arrangeItems({
          menuId: this.$route.params.id,
          menuItems: menuItems,
        })
        .then((response) => {
          this.getMenuItems();
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
          this.$closeLoader();
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
          this.$closeLoader();
        });
    },
    editMenuItem(menuItem) {
      this.tempMenuItemDataEdit = menuItem;
      this.editMenuItemPopUp = true;
    },
    addMenuItem(menuItem) {
      this.menuItem = {
        color: "",
        target: this.menuItemTargets[0].value,
      };
      this.addMenuItemPopUp = true;
    },
    closeModal() {
      this.addMenuItemPopUp = false;
      this.editMenuItemPopUp = false;
    },
    saveMenuItem() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoMenu
        .addItem({ ...this.menuItem, menuId: this.$route.params.id })
        .then((response) => {
          this.getMenuItems();
          this.addMenuItemPopUp = false;
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
          this.$closeLoader();
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
          this.$closeLoader();
        });
    },
    deleteMenuItem() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoMenu
        .deleteItem({
          menuId: this.$route.params.id,
          menuItemId: this.menuItemIdWillBeDeleted,
        })
        .then((response) => {
          this.willDeleteId = null;
          this.getMenuItems();
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
          this.$closeLoader();
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
          this.$closeLoader();
        });
    },
    updateMenuItem(data) {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoMenu
        .editItem({
          menuId: this.$route.params.id,
          menuItemId: data.id,
          title: data.title,
          url: data.url,
          target: data.target,
          iconClass: data.iconClass,
          color: data.color,
        })
        .then((response) => {
          this.editMenuItemPopUp = false;
          this.getMenuItems();
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
          this.$closeLoader();
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
          this.$closeLoader();
        });
    },
    async saveCheckMenuItemExpand(menuItem) {
      const { id, isExpand } = menuItem;
      try {
        // request api from menu
        await this.$api.badasoMenu.menuOptions({
          menu_item_id: id,
          is_expand: !isExpand,
          type: "menu_item",
        });
        // request api from menu item
        const responseMenuItem = await this.$api.badasoMenu.browseItem({
          menuId: this.$route.params.id,
        });
        this.menuItems = responseMenuItem.data.menuItems.map((item) => {
          const _item = item;
          _item.editItem = false;
          _item.title = item.title ? item.title : "";
          _item.iconClass = item.iconClass ? item.iconClass : "";
          _item.color = item.color ? item.color : "#000000";
          return _item;
        });
        this.savedItems = [...responseMenuItem.data.menuItems];
        this.flatSavedItems = this.flattenItems([
          ...responseMenuItem.data.menuItems,
        ]);
        this.$store.commit("badaso/FETCH_MENU");
      } catch (error) {
        this.$vs.notify({
          title: this.$t("alert.danger"),
          text: error.message,
          color: "danger",
        });
      }
    },
  },
};
</script>
