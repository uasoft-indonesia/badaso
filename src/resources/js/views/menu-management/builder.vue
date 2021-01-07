<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4" v-if="$helper.isAllowed('add_menu_items')">
        <div style="float: right">
          <vs-button
            color="primary"
            type="relief"
            @click="openPopup=true"
            ><vs-icon icon="add"></vs-icon> Add Item</vs-button
          >
          <vs-popup
            class="holamundo"
            title="Add Menu Item"
            :active.sync="openPopup"
          >
            <vs-row>
              <badaso-text v-model="menuItem.title" size="12" label="Title" placeholder=""></badaso-text>
              <badaso-text v-model="menuItem.url" size="12" label="Url" placeholder=""></badaso-text>
              <badaso-select v-model="menuItem.target" size="12" label="Target" :items="menuItemTargets" placeholder=""></badaso-select>
              <badaso-text v-model="menuItem.iconClass" size="12" label="Icon" placeholder="" additionalInfo="Use&nbsp;<a href='https://material.io/resources/icons/?style=baseline' target='_blank'>material design icon</a>"></badaso-text>
              <badaso-color-picker
              size="12"
              v-model="menuItem.color"
            ></badaso-color-picker>
            </vs-row>
            <vs-row vs-type="flex" vs-justify="flex-end">
              <vs-col vs-lg="12" vs-type="flex" vs-align="flex-end">
                <vs-button v-if="menuItem.id" color="primary" @click="updateMenuItem()" type="filled">Update</vs-button>
                <vs-button v-else color="primary" @click="addMenuItem()" type="filled">Add</vs-button>
                <vs-button color="danger" type="flat">Cancel</vs-button>
              </vs-col>
            </vs-row>
          </vs-popup>
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('edit_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Menu Item</h3>
          </div>
          <div class="row">
            <div class="col-12">
              <Tree
                :data="menuItems"
                draggable="draggable"
                cross-tree="cross-tree"
                :ondragend="saveMenuOrder()"
              >
                <div
                  slot-scope="{ data, store }"
                  class="data-box"
                  @click="store.toggleOpen(data)"
                >
                  <template v-if="!data.isDragPlaceHolder">
                    <div class="data-display">
                      <div class="text-display">
                        <b v-if="data.children && data.children.length"
                          >{{ data.open ? "-" : "+" }}&nbsp;
                        </b>
                        <b v-else>
                          &nbsp;&nbsp;
                        </b>
                        <span>{{ data.title }}</span>
                      </div>
                    </div>
                    <div class="data-action">
                      <vs-button color="warning" type="relief" @click.stop
                        @click="editMenuItem(data)"
                        v-if="$helper.isAllowed('edit_menu_items')"
                        ><vs-icon icon="edit"></vs-icon
                      ></vs-button>
                      <vs-button
                        color="danger"
                        type="relief"
                        @click.stop
                        @click="openConfirm(data.id)"
                        v-if="$helper.isAllowed('delete_menu_items')"
                        ><vs-icon icon="delete"></vs-icon
                      ></vs-button>
                    </div>
                  </template>
                </div>
              </Tree>
            </div>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>You're not allowed to edit Menu</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import { DraggableTree } from "vue-draggable-nested-tree";
import _ from "lodash";
import BadasoText from "../../components/BadasoText";
import BadasoSelect from "../../components/BadasoSelect";
import BadasoColorPicker from "../../components/BadasoColorPicker";

export default {
  name: "Builder",
  components: {
    BadasoBreadcrumb,
    Tree: DraggableTree,
    BadasoText,
    BadasoSelect,
    BadasoColorPicker
  },
  data: () => ({
    fieldList: [],
    menuItems: [],
    menuItem: {
      color: ''
    },
    savedItems: [],
    flatSavedItems: [],
    arrangeItems: false,
    openPopup:false,
    menuItemIdWillBeDeleted: null,
    menuItemTargets: [
      {
        label: "New Window",
        value: "_blank",
      },
      {
        label: "Same Window",
        value: "_self",
      }
    ],
  }),
  watch: {
    arrangeItems: function(val) {
      if (val) {
        this.startArrangeItems();
      }
    },
  },
  mounted() {
    this.getMenuItems();
  },
  methods: {
    openConfirm(id) {
      this.menuItemIdWillBeDeleted = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteMenuItem,
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    submitForm() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.bread
        .add(this.$caseConvert.snake(this.dataBread))
        .then((response) => {
          this.$vs.loading.close();
          this.$store.commit("FETCH_MENU");
          this.$router.push({ name: "BreadBrowse" });
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    getMenuItems() {
      this.arrangeItems = false;
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .browseItem({
          menuId: this.$route.params.id,
        })
        .then((response) => {
          this.menuItems = response.data.menuItems;
          this.savedItems = [...response.data.menuItems];
          this.flatSavedItems = this.flattenItems([...response.data]);
          this.$vs.loading.close();
        })
        .catch((error) => {
          this.$vs.loading.close();
        });
    },
    saveMenuOrder() {
      const isEqual = _.isEqual(this.menuItems, this.savedItems);
      if (!isEqual) {
        const isChange = this.checkArray(this.menuItems);
        if (isChange === true) {
          this.arrangeItems = true;
        }
      }
    },
    checkArray(items) {
      for (let i = 0; i < items.length; i++) {
        let idx = _.findIndex(this.flatSavedItems, (o) => {
          return o.id == items[i].id;
        });
        if (items[i].children && items[i].children.length > 0) {
          let stat = this.checkArray(items[i].children);
          if (!stat) {
            return false;
          }
        } else {
          if (idx === -1) {
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
          let flattenChildren = this.flattenItems(items[i].children);
          flatten.push(items[i]);
          flatten = flatten.concat(flattenChildren);
        } else {
          flatten.push(items[i]);
        }
      }
      return flatten;
    },
    buildArrangeItems(items) {
      let ls = [];
      for (let i = 0; i < items.length; i++) {
        if (items[i].children && items[i].children.length > 0) {
          let format = {
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
          let format = {
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
      this.$vs.loading({
        type: "sound",
      });
      let menuItems = this.buildArrangeItems(this.menuItems);
      this.$api.menu
        .arrangeItems({
          menuId: this.$route.params.id,
          menuItems: menuItems,
        })
        .then((response) => {
          this.getMenuItems();
          this.$store.commit("FETCH_MENU");
          this.$vs.loading.close();
        })
        .catch((error) => {
          console.log(error);
          this.$vs.loading.close();
        });
    },
    addMenuItem() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .addItem({...this.menuItem, menuId: this.$route.params.id})
        .then((response) => {
          this.getMenuItems();
          this.openPopup = false;
          this.$store.commit("FETCH_MENU");
          this.$vs.loading.close();
        })
        .catch((error) => {
          console.log(error);
          this.$vs.loading.close();
        });
    },
    deleteMenuItem() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .deleteItem({
          menuId: this.$route.params.id,
          menuItemId: this.menuItemIdWillBeDeleted,
        })
        .then((response) => {
          this.willDeleteId = null;
          this.getMenuItems();
          this.$store.commit("FETCH_MENU");
          this.$vs.loading.close();
        })
        .catch((error) => {
          console.log(error);
          this.$vs.loading.close();
        });
    },
    editMenuItem(menuItem) {
      this.menuItem = menuItem;
      this.openPopup = true;
    },
    updateMenuItem() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .editItem({
          menuId: this.$route.params.id,
          menuItemId: this.menuItem.id,
          title: this.menuItem.title,
          url: this.menuItem.url,
          target: this.menuItem.target,
          iconClass: this.menuItem.iconClass,
          color: this.menuItem.color,
        })
        .then((response) => {
          this.openPopup = false;
          this.getMenuItems();
          this.$store.commit("FETCH_MENU");
          this.$vs.loading.close();
        })
        .catch((error) => {
          console.log(error);
          this.$vs.loading.close();
        });
    },
  },
};
</script>

<style>
.drag_icon:hover {
  cursor: all-scroll;
}
.he-tree {
  padding: 20px;
}
.tree-node-inner {
  padding: 5px;
  border: 1px solid #ccc;
  cursor: pointer;
}
.draggable-placeholder-inner {
  border: 1px dashed #0088f8;
  box-sizing: border-box;
  background: rgba(0, 136, 249, 0.09);
  color: #0088f9;
  text-align: center;
  padding: 0;
  display: flex;
  align-items: center;
}
.tree3 .tree-node-inner {
  border: none;
  padding: 0px;
}
.tree3 .tree-node-inner-back:hover {
  background: #ddd;
}
.tree4 .tree-node-inner {
  border: none;
  border-bottom: 1px solid #ccc;
  padding: 5px 10px;
  background: #ccc;
}
.tree4 .draggable-placeholder-inner {
  background-color: orangered;
}

.data-box {
  display: flex;
  justify-content: space-between;
}
.data-display {
  position: relative;
}
.data-action {
  float: right;
}

.text-display {
  width: max-content;
  position: absolute;
  top: 50%;
  transform: translate(0, -50%);
}
</style>
