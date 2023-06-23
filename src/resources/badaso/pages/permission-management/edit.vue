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
              :tooltip="$t('permission.help.alwaysAllow')"
            ></badaso-switch>
            <badaso-switch
              v-model="permission.isPublic"
              size="3"
              :label="$t('permission.edit.field.isPublic')"
              placeholder="Is Public"
              :alert="errors.isPublic"
              :tooltip="$t('permission.help.isPublic')"
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
            <badaso-select-multiple
              :label="$t('permission.edit.field.rolesCanSeeAllData.title')"
              :placeholder="
                $t('permission.edit.field.rolesCanSeeAllData.placeholder')
              "
              v-model="permission.rolesCanSeeAllData"
              size="12"
              :items="roleData ? roleData : []"
            ></badaso-select-multiple>
            <badaso-select
              :label="
                $t('permission.edit.field.fieldIdentifyRelatedUser.title')
              "
              :placeholder="
                $t('permission.edit.field.fieldIdentifyRelatedUser.placeholder')
              "
              v-model="permission.fieldIdentifyRelatedUser"
              size="12"
              :items="fieldData ? fieldData : []"
            ></badaso-select>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card class="action-card">
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
  components: {},
  data: () => ({
    errors: {},
    permission: {
      description: "",
      alwaysAllow: false,
    },
    roleData: [],
    selected: [],
    fieldData: [],
    table: "",
  }),
  mounted() {
    this.getPermissionDetail();
    this.getRole();
  },
  methods: {
    getPermissionDetail() {
      this.$openLoader();
      this.$api.badasoPermission
        .read({
          id: this.$route.params.id,
        })
        .then((response) => {
          this.$closeLoader();
          this.permission = response.data.permission;
          this.permission.alwaysAllow = this.permission.alwaysAllow == 1;
          this.permission.isPublic = this.permission.isPublic == 1;
          this.permission.description =
            this.permission.description == null
              ? ""
              : this.permission.description;
          this.getFieldTable(this.permission.tableName);
          this.permission.rolesCanSeeAllData = this.permission
            .rolesCanSeeAllData
            ? JSON.parse(this.permission.rolesCanSeeAllData)
            : [];
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
    getFieldTable(table) {
      this.$api.badasoCrud
        .read({
          table: table,
        })
        .then((response) => {
          this.fieldData = response.data.crud.dataRows;
          for (const key in this.fieldData) {
            if (this.fieldData[key].displayName) {
              this.fieldData[key].label = this.fieldData[key].field;
              delete this.fieldData[key].displayName;
            }
            if (this.fieldData[key].field) {
              this.fieldData[key].value = this.fieldData[key].field;
              delete this.fieldData[key].field;
            }
          }
          this.fieldData.push({
            label: "user_id",
            value: "user_id",
          });
        })
        .catch(() => {
          this.$closeLoader();
        });
    },
    getRole() {
      this.$api.badasoRole
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.roleData = response.data.roles;
          for (const key in this.roleData) {
            if (this.roleData[key].displayName) {
              this.roleData[key].label = this.roleData[key].displayName;
              delete this.roleData[key].displayName;
            }
            if (this.roleData[key].name) {
              this.roleData[key].value = this.roleData[key].name;
              delete this.roleData[key].name;
            }
          }
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
    submitForm() {
      this.errors = {};
      this.$openLoader();
      this.$api.badasoPermission
        .edit(this.permission)
        .then((response) => {
          this.$closeLoader();
          this.$router.push({ name: "PermissionManagementBrowse" });
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
