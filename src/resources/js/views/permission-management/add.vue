<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('add_permissions')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Add Permission</h3>
          </div>
          <vs-row>
            <badaso-text v-model="permission.key" size="6" label="Key" placeholder="Key"></badaso-text>
            <badaso-switch v-model="permission.alwaysAllow" size="6" label="Alway Allow" placeholder="Always Allow"></badaso-switch>
            <badaso-textarea v-model="permission.description" size="12" label="Description" placeholder="Description"></badaso-textarea>
            <badaso-text v-model="permission.tableName" size="12" label="Table Name" placeholder="Table Name"></badaso-text>
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
              <h3>You're not allowed to add Permission</h3>
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
    permission: {
        description: '',
        key: '',
        tableName: '',
        alwaysAllow: false
    }
  }),
  mounted() {
  },
  methods: {
    submitForm() {
      this.$vs.loading();
      this.$api.permission
        .add(this.permission)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({name: "PermissionBrowse"})
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({title:'Danger',text:error.message,color:'danger'})
        })
    },
  },
};
</script>