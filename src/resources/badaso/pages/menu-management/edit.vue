<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("menu.edit.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="menu.key"
              size="6"
              :label="$t('menu.edit.field.key.title')"
              :placeholder="$t('menu.edit.field.key.placeholder')"
              :alert="errors.key"
              :tooltip="$t('menu.help.key')"
            ></badaso-text>
            <badaso-text
              v-model="menu.displayName"
              size="6"
              :label="$t('menu.edit.field.displayName.title')"
              :placeholder="$t('menu.edit.field.displayName.placeholder')"
              :alert="errors.displayName"
            ></badaso-text>
            <badaso-text
              v-model="menu.icon"
              size="6"
              :label="$t('menu.add.field.icon.title')"
              :placeholder="$t('menu.add.field.icon.placeholder')"
              :additionalInfo="
                $t('menu.builder.popup.add.field.icon.description')
              "
              :alert="errors.icon"
            ></badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t("menu.add.button") }}
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
              <h3>{{ $t("menu.warning.notAllowedToEdit") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "MenuManagementBrowse",
  components: {},
  data: () => ({
    errors: {},
    menu: {
      menuId: null,
      displayName: "",
      key: "",
      icon: "",
    },
  }),
  mounted() {
    this.getMenuDetail();
  },
  methods: {
    submitForm() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoMenu
        .edit(this.menu)
        .then((response) => {
          this.$closeLoader();
          this.$router.push({ name: "MenuManagementBrowse" });
          this.$store.commit("badaso/FETCH_MENU");
          this.$store.commit("badaso/FETCH_CONFIGURATION_MENU");
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    getMenuDetail() {
      this.$openLoader();
      this.$api.badasoMenu
        .read({
          menuId: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.menu = response.data.menu;
          this.menu.menuId = this.menu.id;
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
