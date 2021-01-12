<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add Menu</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="menu.key"
              size="6"
              label="Key"
              placeholder="menu_key"
              :alert="errors.key"
            ></badaso-text>
            <badaso-text
              v-model="menu.displayName"
              size="6"
              label="Display Name"
              placeholder="Display Name"
              :alert="errors.displayName"
            ></badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
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
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>You're not allowed to add Menu</h3>
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
    errors: {},
    menu: {
      displayName: "",
      key: "",
    },
  }),
  methods: {
    submitForm() {
      this.errors = {}
      this.$vs.loading();
      this.$api.menu
        .add(this.menu)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({ name: "MenuBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors
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
