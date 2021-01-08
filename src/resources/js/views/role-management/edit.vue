<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('edit_roles')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Edit Role</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="role.name"
              size="6"
              label="Name"
              placeholder="Name"
              :alert="errors.name"
            ></badaso-text>
            <badaso-text
              v-model="role.displayName"
              size="6"
              label="Display Name"
              placeholder="Display Name"
              :alert="errors.displayName"
            ></badaso-text>
            <badaso-textarea
              v-model="role.description"
              size="12"
              label="Description"
              placeholder="Description"
              :alert="errors.description"
            ></badaso-textarea>
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
              <h3>You're not allowed to edit Role</h3>
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
import BadasoSwitch from "../../components/BadasoSwitch.vue";
import BadasoTextarea from "../../components/BadasoTextarea.vue";

export default {
  name: "Browse",
  components: {
    BadasoText,
    BadasoBreadcrumb,
    BadasoSwitch,
    BadasoTextarea,
  },
  data: () => ({
    errors: {},
    role: {
      description: "",
      name: "",
      displayName: "",
    },
  }),
  mounted() {
    this.getRoleDetail();
  },
  methods: {
    getRoleDetail() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.role = response.data.role;
          this.role.name = this.role.name ? this.role.name : "";
          this.role.displayName = this.role.displayName
            ? this.role.displayName
            : "";
          this.role.description = this.role.description
            ? this.role.description
            : "";
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
    submitForm() {
      this.errors = {}
      this.$vs.loading({
        type: "sound",
      });
      this.$api.role
        .edit(this.role)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({ name: "RoleBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors;
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
