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
              :disabled="firebase.statusApiKey"
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
              :disabled="firebase.statusAuthDomain"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.projectId')"
              :placeholder="$t('firebase.form.projectId')"
              :alert="errors.projectId"
              v-model="firebase.projectId"
              :disabled="firebase.statusArojectId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.storageBucket')"
              :placeholder="$t('firebase.form.storageBucket')"
              :alert="errors.storageBucket"
              v-model="firebase.storageBucket"
              :disabled="firebase.statusAtorageBucket"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.messagingSenderId')"
              :placeholder="$t('firebase.form.messagingSenderId')"
              :alert="errors.messagingSenderId"
              v-model="firebase.messagingSenderId"
              :disabled="firebase.statusAessagingSenderId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.appId')"
              :placeholder="$t('firebase.form.appId')"
              :alert="errors.appId"
              v-model="firebase.appId"
              :disabled="firebase.statusAppId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.measurementId')"
              :placeholder="$t('firebase.form.measurementId')"
              :alert="errors.measurementId"
              v-model="firebase.measurementId"
              :disabled="firebase.statusAeasurementId"
            ></badaso-text>
            <badaso-text
              size="6"
              :label="$t('firebase.form.serverKey')"
              :placeholder="$t('firebase.form.serverKey')"
              :alert="errors.serverKey"
              v-model="firebase.serverKey"
              :disabled="firebase.statusAerverKey"
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
      apiKey: null,
      statusApiKey: true,
      authDomain: null,
      statusAuthDomain: false,
      projectId: null,
      statusArojectId: false,
      storageBucket: null,
      statusAtorageBucket: false,
      messagingSenderId: null,
      statusAessagingSenderId: false,
      appId: null,
      statusAppId: false,
      measureId: null,
      statusAeasureId: false,
      serverKey: null,
      statusAerverKey: false,
      activeStatus: null,
      statusActiveStatus: false,
    },
  }),
  mounted() {},
  methods: {},
};
</script>
