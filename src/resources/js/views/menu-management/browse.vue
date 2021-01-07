<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief"
            :to="{name: 'MenuAdd'}"
            ><vs-icon icon="add"></vs-icon> Add</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('browse_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Menu</h3>
          </div>
          <div>
            <vs-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="menus"
              stripe
              description
              :description-items="descriptionItems"
              description-title="Registries"
              description-connector="of"
              description-body="Pages"
            >
              <template slot="thead">
                <vs-th sort-key="key"> Key </vs-th>
                <vs-th sort-key="displayName"> Display Name </vs-th>
                <vs-th>Action</vs-th>
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
                  <vs-td
                    style="width: 1%; white-space: nowrap"
                  >
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('edit_menus')"
                      :to="{name: 'MenuBuilder', params: {id: data[index].id}}"
                      ><vs-icon icon="list"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="warning"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('edit_menus')"
                      :to="{name: 'MenuEdit', params: {id: data[index].id}}"
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('delete_menus')"
                      @click="openConfirm(data[index].id)"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>You're not allowed to browse Menu</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";
export default {
  components: { BadasoBreadcrumb },
  name: "Browse",
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
        title: `Confirm`,
        text: "Are you sure?",
        accept: this.deleteMenu,
        cancel: () => {
          this.willDeleteId = null;
        }
      });
    },
    getMenuList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.menus = response.data.menus;
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
    deleteMenu() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .delete({
          menu_id: this.willDeleteId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getMenuList();
          this.$store.commit("FETCH_MENU");
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
  },
};
</script>
