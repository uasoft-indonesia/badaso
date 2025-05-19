<template>
  <div>
    <badaso-breadcrumb-row>
      <template #action>
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'MenuManagementAdd' }"
        >
          <vs-icon icon="add" /> {{ $t("action.add") }}
        </vs-button>
      </template>
    </badaso-breadcrumb-row>

    <vs-row v-if="$helper.isAllowed('browse_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <template #header>
            <h3>{{ $t("menu.title") }}</h3>
          </template>

          <div>
            <Tree
              :data="menus"
              draggable="draggable"
              cross-tree="cross-tree"
              class="menu-management__tree"
              @dragend="saveMenuOrder"
            >
              <vs-row vs-w="12" vs-justify="space-between" v-slot="{ data }">
                <vs-col vs-type="flex" vs-justify="flex-start" vs-align="center" vs-w="2">
                  <strong>{{ data.displayName }}</strong>
                </vs-col>
                <vs-col vs-type="flex" vs-justify="flex-start" vs-align="center" vs-w="2">
                  <vs-checkbox
                    :model-value="data.isShowHeader"
                    @update:model-value="saveCheckMenuShowHeader(data.id)"
                  >
                    {{ $t("menu.options.showHeader") }}
                  </vs-checkbox>
                </vs-col>
                <vs-col vs-type="flex" vs-justify="flex-start" vs-align="center" vs-w="2">
                  <vs-checkbox
                    :model-value="data.isExpand"
                    @update:model-value="saveCheckMenuExpand(data)"
                  >
                    {{ $t("menu.options.expand") }}
                  </vs-checkbox>
                </vs-col>
                <vs-col vs-type="flex" vs-justify="flex-end" vs-align="center" vs-w="2">
                  <badaso-dropdown vs-trigger-click>
                    <vs-button size="large" type="flat" icon="more_vert" />
                    <vs-dropdown-menu>
                      <badaso-dropdown-item
                        icon="list"
                        v-if="$helper.isAllowed('edit_menus')"
                        :to="{ name: 'MenuManagementBuilder', params: { id: data.id } }"
                      >
                        Manage Items
                      </badaso-dropdown-item>
                      <badaso-dropdown-item
                        icon="edit"
                        v-if="$helper.isAllowed('edit_menus')"
                        :to="{ name: 'MenuManagementEdit', params: { id: data.id } }"
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
import { ref, onMounted } from 'vue';
// import { DraggableTree } from 'vue-draggable-nested-tree';
import Draggable from "vue3-draggable";

export default {
  name: 'MenuManagementBrowse',
  components: { Draggable,},
  setup() {
    const menus = ref([]);
    const saveOrder = ref([]);
    const willDeleteId = ref(null);

    const getMenuList = async () => {
      try {
        await $openLoader();
        const response = await $api.badasoMenu.browse();
        menus.value = response.data.menus;
        console.log(menus.value, ' menus');
      } catch (error) {
        $vs.notify({ title: $t('alert.danger'), text: error.message, color: 'danger' });
      } finally {
        $closeLoader();
      }
    };

    const saveMenuOrder = () => {
      const order = menus.value.map((item) => item.id).filter((id) => id !== undefined);
      if (JSON.stringify(order) !== JSON.stringify(saveOrder.value)) {
        saveOrder.value = order;
        $api.badasoMenu.menuOptions({ order })
          .then(() => $store.commit('badaso/FETCH_MENU'))
          .catch((error) => {
            $vs.notify({ title: $t('alert.danger'), text: error.message, color: 'danger' });
          });
      }
    };

    const saveCheckMenuExpand = (menu) => {
      const { id, isExpand } = menu;
      $api.badasoMenu
        .menuOptions({ menu_id: id, is_expand: !isExpand, type: 'menu' })
        .then(() => $store.commit('badaso/FETCH_MENU'))
        .catch((error) => {
          $vs.notify({ title: $t('alert.danger'), text: error.message, color: 'danger' });
        });
    };

    const saveCheckMenuShowHeader = (menuId) => {
      $api.badasoMenu
        .menuOptions({ menu_id: menuId, is_show_header: 'event' })
        .then(() => $store.commit('badaso/FETCH_MENU'))
        .catch((error) => {
          $vs.notify({ title: $t('alert.danger'), text: error.message, color: 'danger' });
        });
    };

    const openConfirm = (id) => {
      willDeleteId.value = id;
      $vs.dialog({
        type: 'confirm',
        color: 'danger',
        title: $t('action.delete.title'),
        text: $t('action.delete.text'),
        accept: deleteMenu,
        acceptText: $t('action.delete.accept'),
        cancelText: $t('action.delete.cancel'),
        cancel: () => {
          willDeleteId.value = null;
        },
      });
    };

    const deleteMenu = async () => {
      try {
        await $openLoader();
        await $api.badasoMenu.delete({ menu_id: willDeleteId.value });
        getMenuList();
        $store.commit('badaso/FETCH_MENU');
      } catch (error) {
        $vs.notify({ title: $t('alert.danger'), text: error.message, color: 'danger' });
      } finally {
        $closeLoader();
      }
    };

    onMounted(getMenuList);

    return {
      menus,
      saveMenuOrder,
      saveCheckMenuExpand,
      saveCheckMenuShowHeader,
      openConfirm,
      deleteMenu,
    };
  },
};
</script>
