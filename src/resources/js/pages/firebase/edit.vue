<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_menus')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("firebase.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="firebase.apiKey"
              size="6"
              :label="$t('firebase.form.apiKey')"
              :placeholder="$t('firebase.form.apiKey')"
              :alert="errors.apiKey"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.authDomain')"
              :placeholder="$t('firebase.form.authDomain')"
              :alert="errors.authDomain"
              v-model="firebase.authDomain"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.projectId')"
              :placeholder="$t('firebase.form.projectId')"
              :alert="errors.projectId"
              v-model="firebase.projectId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.storageBucket')"
              :placeholder="$t('firebase.form.storageBucket')"
              :alert="errors.storageBucket"
              v-model="firebase.storageBucket"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.messagingSenderId')"
              :placeholder="$t('firebase.form.messagingSenderId')"
              :alert="errors.messagingSenderId"
              v-model="firebase.messagingSenderId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.appId')"
              :placeholder="$t('firebase.form.appId')"
              :alert="errors.appId"
              v-model="firebase.appId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.measurementId')"
              :placeholder="$t('firebase.form.measurementId')"
              :alert="errors.measurementId"
              v-model="firebase.measurementId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.serverKey')"
              :placeholder="$t('firebase.form.serverKey')"
              :alert="errors.serverKey"
              v-model="firebase.serverKey"
            ></badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <div slot="header">
            <h3>{{ $t("firebase.feature") }}</h3>
          </div>
          <vs-row>
            <vs-col vs-lg="12">
              <badaso-switch
                :label="$t('firebase.features.firebaseCloudMessage')"
                :placeholder="$t('firebase.features.firebaseCloudMessage')"
                :v-model="null"
                size="12"
                :alert="null"
              ></badaso-switch>
            </vs-col>
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
import BadasoText from "../../components/BadasoText.vue";

export default {
  name: "FirebaseEdit",
  components: { BadasoText },
  data: () => ({
    errors: {},
    firebase: {
      apiKey : null,
      authDomain : null,
      projectId : null,
      storageBucket : null,
      messagingSenderId : null,
      appId : null,
      measureId : null,
      serverKey : null,
      activeStatus : null,
    },
  }),
  mounted() {
    
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
