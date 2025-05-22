<template>
  <div>
    <badaso-breadcrumb-row></badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('add_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("menu.add.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="menu.key"
              size="6"
              :label="$t('menu.add.field.key.title')"
              :placeholder="$t('menu.add.field.key.placeholder')"
              :alert="errors.key"
              :tooltip="$t('menu.help.key')"
            ></badaso-text>
            <badaso-text
              v-model="menu.displayName"
              size="6"
              :label="$t('menu.add.field.displayName.title')"
              :placeholder="$t('menu.add.field.displayName.placeholder')"
              :alert="errors.displayName"
            ></badaso-text>
            <badaso-text
              v-model="menu.icon"
              size="6"
              :label="$t('menu.add.field.icon.title')"
              :placeholder="$t('menu.add.field.icon.placeholder')"
              :alert="errors.icon"
              :additionalInfo="
                $t('menu.builder.popup.add.field.icon.description')
              "
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
              <h3>{{ $t("menu.warning.notAllowedToAdd") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "MenuManagementAdd",
  components: {},
  data: () => ({
    errors: {},
    menu: {
      displayName: "",
      key: "",
      icon: "",
    },
  }),
  methods: {
    submitForm() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoMenu
        .add(this.menu)
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
  },
};
</script>
