<template>
  <div>
    <badaso-breadcrumb-row> </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('edit_permissions')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("permission.edit.title") }}</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="permission.key"
              size="6"
              :label="$t('permission.edit.field.key.title')"
              :placeholder="$t('permission.edit.field.key.placeholder')"
              readonly
              :alert="errors.key"
            ></badaso-text>
            <badaso-switch
              v-model="permission.alwaysAllow"
              size="3"
              :label="$t('permission.edit.field.alwaysAllow')"
              placeholder="Always Allow"
              :alert="errors.alwaysAllow"
            ></badaso-switch>
            <badaso-switch
              v-model="permission.isPublic"
              size="3"
              :label="$t('permission.edit.field.isPublic')"
              placeholder="Is Public"
              :alert="errors.isPublic"
            ></badaso-switch>
            <badaso-textarea
              v-model="permission.description"
              size="12"
              :label="$t('permission.edit.field.description.title')"
              :placeholder="$t('permission.edit.field.description.placeholder')"
              :alert="errors.description"
            ></badaso-textarea>
            <badaso-text
              v-model="permission.tableName"
              size="12"
              :label="$t('permission.edit.field.tableName.title')"
              :placeholder="$t('permission.edit.field.tableName.placeholder')"
              :alert="errors.tableName"
            ></badaso-text>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon>
                {{ $t("permission.edit.button") }}
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
              <h3>{{ $t("permission.warning.notAllowedToEdit") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "PermissionManagementEdit",
  components: {
  },
  data: () => ({
    errors: {},
    permission: {
      description: "",
      alwaysAllow: false,
    },
  }),
  mounted() {
    this.getPermissionDetail();
  },
  methods: {
    getPermissionDetail() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.permission
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.permission = response.data.permission;
          this.permission.alwaysAllow = this.permission.alwaysAllow === 1;
          this.permission.isPublic = this.permission.isPublic === 1;
          this.permission.description =
            this.permission.description === null
              ? ""
              : this.permission.description;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    submitForm() {
      this.errors = {};
      this.$vs.loading();
      this.$api.permission
        .edit(this.permission)
        .then((response) => {
          this.$vs.loading.close();
          this.$router.push({ name: "PermissionManagementBrowse" });
        })
        .catch((error) => {
          this.errors = error.errors;
          this.$vs.loading.close();
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
