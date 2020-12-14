<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Edit Menu</h3>
          </div>
          <vs-row>
            <badaso-text v-model="menu.key" size="6" label="Key" placeholder="menu_key"></badaso-text>
            <badaso-text v-model="menu.displayName" size="6" label="Display Name" placeholder="Display Name"></badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Save
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoText from "../../components/BadasoText";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";

export default {
  name: "Browse",
  components: {
    BadasoText,
    BadasoBreadcrumb,
  },
  data: () => ({
    menu: {
        menuId: null,
        displayName: "",
        key: "",
    }
  }),
  mounted() {
        this.getMenuDetail();
  },
  methods: {
    submitForm() {
      this.$vs.loading();
      this.$api.menu
        .edit(this.menu)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({name: "MenuBrowse"})
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({title:'Danger',text:error.message,color:'danger'})
        })
    },
    getMenuDetail() {
        this.$vs.loading({
        type: "sound",
      });
      this.$api.menu
        .read({
            menuId: this.$route.params.id
        })
        .then((response) => {
          this.$vs.loading.close();
          this.menu = response.record;
          this.menu.menuId = this.menu.id
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    }
  },
};
</script>