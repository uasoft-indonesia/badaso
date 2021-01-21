<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_roles')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('role.add.title') }}</h3>
          </div>
          <vs-row>
            <badaso-text v-model="role.name" size="6" :label="$t('role.add.field.name.title')" :placeholder="$t('role.add.field.name.placeholder')" :alert="errors.name"></badaso-text>
            <badaso-text v-model="role.displayName" size="6" :label="$t('role.add.field.displayName.title')" :placeholder="$t('role.add.field.displayName.placeholder')" :alert="errors.displayName"></badaso-text>
            <badaso-textarea v-model="role.description" size="12" :label="$t('role.add.field.description.title')" :placeholder="$t('role.add.field.description.placeholder')" :alert="errors.description"></badaso-textarea>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t('role.add.button') }}
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
              <h3>{{ $t('role.warning.notAllowedToAdd') }}</h3>
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
import BadasoSwitch from '../../components/BadasoSwitch.vue';
import BadasoTextarea from '../../components/BadasoTextarea.vue';

export default {
  name: "Browse",
  components: {
    BadasoText,
    BadasoBreadcrumb,
    BadasoSwitch,
    BadasoTextarea
  },
  data: () => ({
    errors: {},
    role: {
        description: '',
        name: '',
        displayName: ''
    }
  }),
  mounted() {
  },
  methods: {
    submitForm() {
      this.errors = {}
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .add(this.role)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({name: "RoleBrowse"})
        })
        .catch((error) => {
          this.errors = error.errors
          this.$vs.loading.close();
          this.$vs.notify({title:this.$t('alert.danger'),text:error.message,color:'danger'})
        })
    },
  },
};
</script>