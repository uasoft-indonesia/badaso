<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_roles')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("role.edit.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="role.name"
              size="6"
              :label="$t('role.edit.field.name.title')"
              :placeholder="$t('role.edit.field.name.placeholder')"
              :alert="errors.name"
            ></badaso-text>
            <badaso-text
              v-model="role.displayName"
              size="6"
              :label="$t('role.edit.field.displayName.title')"
              :placeholder="$t('role.edit.field.displayName.placeholder')"
              :alert="errors.displayName"
            ></badaso-text>
            <badaso-textarea
              v-model="role.description"
              size="12"
              :label="$t('role.edit.field.description.title')"
              :placeholder="$t('role.edit.field.description.placeholder')"
              :alert="errors.description"
            ></badaso-textarea>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> {{ $t("role.edit.button") }}
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
              <h3>{{ $t("role.warning.notAllowedToEdit") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "RoleManagementEdit",
  components: {},
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
      this.$openLoader();
      this.$api.badasoRole
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
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
          this.$closeLoader();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    submitForm() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoRole
        .edit(this.role)
        .then((response) => {
          this.$closeLoader();
          this.$router.push({ name: "RoleManagementBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$closeLoader();
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
