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
            <h3>Add Role</h3>
          </div>
          <vs-row>
            <badaso-text v-model="role.name" size="6" label="Name" placeholder="Name"></badaso-text>
            <badaso-text v-model="role.displayName" size="6" label="Display Name" placeholder="Display Name"></badaso-text>
            <badaso-textarea v-model="role.description" size="12" label="Description" placeholder="Description"></badaso-textarea>
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
      this.$vs.loading();
      this.$api.role
        .add(this.role)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({name: "RoleBrowse"})
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({title:'Danger',text:error.message,color:'danger'})
        })
    },
  },
};
</script>