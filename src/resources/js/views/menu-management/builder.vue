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
            ><vs-icon icon="add"></vs-icon> Add Item</vs-button
          >
        </div>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Menu Item</h3>
          </div>
          <vs-row>
            <vs-col col-lg="12" style="overflow-x: auto">
              <table class="table">
                <draggable v-model="menuItems" tag="tbody">
                  <tr :key="index" v-for="(item, index) in menuItems">
                    <td>
                      <vs-icon
                        icon="drag_indicator"
                        class="drag_icon"
                      ></vs-icon>
                    </td>
                    <td>
                      {{ item.title }}
                    </td>
                    <vs-td style="width: 1%; white-space: nowrap">
                      <vs-button color="warning" type="relief" @click.stop
                        ><vs-icon icon="edit"></vs-icon
                      ></vs-button>
                      <vs-button color="danger" type="relief" @click.stop
                        @click="openConfirm(item.id)"
                        ><vs-icon icon="delete"></vs-icon
                      ></vs-button>
                    </vs-td>
                  </tr>
                </draggable>
              </table>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import draggable from "vuedraggable";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";

export default {
  name: "Browse",
  components: {
    draggable,
    BadasoBreadcrumb,
  },
  data: () => ({
    fieldList: [],
    menuItems: [],
  }),
  computed: {},
  mounted() {
    this.getMenuItems();
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
      this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .browseItem({
          menuId: this.$route.params.id,
        })
        .then((response) => {
          this.menuItems = response.records;
          this.$vs.loading.close();
        })
        .catch((error) => {
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
</style>
