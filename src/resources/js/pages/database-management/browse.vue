<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'DatabaseManagementAdd' }"
          v-if="$helper.isAllowed('add_database')"
          ><vs-icon icon="add"></vs-icon>
          {{ $t("database.browse.addButton") }}</vs-button
        >
        <vs-button
          color="success"
          type="relief"
          @click="openRollbackDialog()"
          v-if="$helper.isAllowed('rollback_database')"
          ><vs-icon icon="refresh"></vs-icon>
          {{ $t("database.browse.rollbackButton") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>

    <vs-popup
      :title="$t('database.browse.warning.title')"
      :active.sync="isNotMigrated"
      @close="isNotMigrated = true"
      class="database-management__popup"
      button-close-hidden
    >
      <p>{{ $t("database.browse.warning.notAllowed") }}</p>
      <p v-for="(data, index) in notMigratedFile" :key="index">{{ data }}</p>

      <vs-divider class="database-management__divider"></vs-divider>

      <div class="database-management__popup-sync">
        <vs-button color="warning" type="relief" @click="goBack()"
          ><vs-icon icon="chevron_left"></vs-icon>
          {{ $t("database.browse.goBackButton") }}</vs-button
        >
        <vs-button
          color="danger"
          type="relief"
          @click="confirmDelete()"
          v-if="$helper.isAllowed('delete_migration')"
          ><vs-icon icon="delete"></vs-icon>
          {{ $t("database.browse.deleteMigrationButton") }}</vs-button
        >
        <vs-button
          color="success"
          type="relief"
          @click="migrate()"
          v-if="$helper.isAllowed('migrate_database')"
          ><vs-icon icon="arrow_upward"></vs-icon>
          {{ $t("database.browse.migrateButton") }}</vs-button
        >
      </div>
    </vs-popup>

    <vs-popup
      @close="
        rollbackDialog = false;
        $v.$reset();
        isDeleteFile = false;
      "
      @accept="rollback"
      :active.sync="rollbackDialog"
      :accept-text="$t('action.delete.accept')"
      :cancel-text="$t('action.delete.cancel')"
      :title="$t('database.rollback.title')"
      color="success"
      class="database-management__popup"
    >
      <vs-row>
        <vs-table :data="migration" class="database-management__table">
          <template slot="thead">
            <vs-th sort-key="migration">
              {{ $t("database.migration.header.migration") }}
            </vs-th>
            <vs-th> {{ $t("crud.header.action") }} </vs-th>
          </template>
          <template slot-scope="{ data }">
            <vs-tr
              :data="table"
              :key="index"
              stripe
              v-for="(table, index) in data"
            >
              <vs-td :data="data[index].migration">
                {{ data[index].migration }}
              </vs-td>
              <vs-td>
                <vs-checkbox
                  v-model="willRollbackIndex"
                  :vs-value="index"
                  @change="setRollbackIndex(data)"
                  :disabled="disableCheckbox(index)"
                ></vs-checkbox>
              </vs-td>
            </vs-tr>
          </template>
        </vs-table>
      </vs-row>
      <vs-row vs-align="center" class="database-management__popup-footer">
        <vs-col vs-lg="12" vs-sm="12" vs-align="center">
          <div v-if="$v.$dirty">
            <p v-if="$v.$invalid" class="is-error">
              {{ $t("database.rollback.invalid") }}
            </p>
          </div>
        </vs-col>
        <vs-col vs-lg="12" vs-sm="12" vs-align="center">
          <vs-row vs-align="center">
            <vs-spacer></vs-spacer>
            <vs-checkbox v-model="isDeleteFile">{{
              $t("database.rollback.checkbox")
            }}</vs-checkbox>
            <vs-button
              color="danger"
              type="relief"
              @click="confirmRollback()"
              v-if="$helper.isAllowed('rollback_database')"
            >
              {{ $t("database.migration.button.rollback") }}
            </vs-button>
          </vs-row>
        </vs-col>
      </vs-row>
    </vs-popup>
    <vs-popup
      :active.sync="errorDatabase"
      :title="$t('database.browse.fieldNotSupport.title')"
      color="success"
      class="database-management__popup"
    >
      <vs-row>
        <vs-col>
          <p>
            {{ $t("database.browse.fieldNotSupport.text") }}
          </p>
          <br />
          <p>
            {{ $t("database.browse.fieldNotSupport.tableList") }}
          </p>
          <p>
            {{ errorTable }}
          </p>
        </vs-col>
      </vs-row>
      <vs-row vs-align="center" class="database-management__popup-footer">
        <vs-col vs-lg="12" vs-sm="12" vs-align="center">
          <vs-row vs-align="center">
            <vs-spacer></vs-spacer>
            <div class="database-management__popup-sync">
              <vs-button color="warning" type="relief" @click="goBack()">
                {{ $t("database.browse.goBackButton") }}
              </vs-button>
              <vs-button
                color="success"
                type="relief"
                href="https://badaso-docs.uatech.co.id/crud-generator/datatype"
              >
                {{ $t("database.browse.fieldNotSupport.button.visitDocs") }}
              </vs-button>
            </div>
          </vs-row>
        </vs-col>
      </vs-row>
    </vs-popup>
    <vs-row v-if="$helper.isAllowed('browse_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("database.browse.title") }}</h3>
          </div>
          <badaso-alert-block>
            <template slot="title">{{
              $t("database.edit.warning.title")
            }}</template>
            <template slot="desc">{{
              $t("database.edit.warning.crud")
            }}</template>
          </badaso-alert-block>
          <div>
            <badaso-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="tables"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('database.add.footer.descriptionTitle')"
              :description-connector="
                $t('database.add.footer.descriptionConnector')
              "
              :description-body="$t('database.add.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="tableName">
                  {{ $t("crud.header.table") }}
                </vs-th>
                <vs-th> {{ $t("crud.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  :data="table"
                  :key="index"
                  v-for="(table, index) in data"
                >
                  <vs-td :data="data[index].tableName">
                    {{ data[index].tableName }}
                  </vs-td>
                  <vs-td class="badaso-table__td">
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="edit"
                          v-if="
                            $helper.isAllowed('edit_database') &&
                            data[index].isCanEdit
                          "
                          :to="{
                            name: 'DatabaseManagementAlter',
                            params: { tableName: data[index].tableName },
                          }"
                        >
                          {{ $t("database.browse.alterButton") }}
                        </badaso-dropdown-item>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="openConfirm(data[index].tableName)"
                          v-if="
                            $helper.isAllowed('delete_database') &&
                            data[index].isCanDrop
                          "
                        >
                          {{ $t("database.browse.dropButton") }}
                        </badaso-dropdown-item>
                        <badaso-dropdown-item v-else>
                          {{ $t("database.browse.warning.empty") }}
                        </badaso-dropdown-item>
                      </vs-dropdown-menu>
                    </badaso-dropdown>
                  </vs-td>
                </vs-tr>
              </template>
            </badaso-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t("crud.warning.notAllowed") }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
import { required } from "vuelidate/lib/validators";

export default {
  components: {},
  name: "DatabaseManagementBrowse",
  data: () => ({
    descriptionItems: [10, 50, 100],
    selected: [],
    tables: [],
    tableName: null,
    rollbackDialog: false,
    rollbackSteps: 0,
    migration: [],
    willRollbackIndex: [],
    willRollbackFile: [],
    rollbackIndex: null,
    message: null,
    isNotMigrated: false,
    notMigratedFile: [],
    isDeleteFile: false,
    errorDatabase: false,
    errorTable: "",
  }),
  validations: {
    willRollbackFile: {
      required,
    },
    willRollbackIndex: {
      required,
    },
  },
  mounted() {
    this.getStatusMigration();
    this.getTableList();
  },
  methods: {
    goBack() {
      this.$router.back();
    },
    disableCheckbox(index) {
      return this.willRollbackIndex.includes(index - 1);
    },
    getStatusMigration() {
      this.$api.badasoDatabase
        .check()
        .then((response) => {
          this.isNotMigrated = response.data.notMigrated;
          this.notMigratedFile = response.data.data;
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
    },
    openConfirm(tableName) {
      this.tableName = tableName;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteDatabase,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.tableName = null;
        },
      });
    },
    getTableList() {
      this.$openLoader();
      this.$api.badasoCrud
        .browse()
        .then((response) => {
          this.$closeLoader();
          this.tables = response.data.tablesWithCrudData;
          this.tables.map((value, index) => {
            this.$set(value, "isCanEdit", value.crudData == null);
            this.$set(value, "isCanDrop", value.crudData == null);
          });
        })
        .catch((error) => {
          this.$closeLoader();
          if (error.message.indexOf("Unknown database") == 0) {
            this.errorTable = "- " + error.errors[2].args[0];
            this.errorDatabase = true;
          } else {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }
        });
    },
    deleteDatabase() {
      this.$openLoader();
      this.$api.badasoDatabase
        .delete({
          table: this.tableName,
        })
        .then((response) => {
          this.$closeLoader();
          this.getTableList();
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: response.data,
            color: "success",
          });
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
    getMigration() {
      this.$openLoader();

      this.$api.badasoDatabase
        .browseMigration()
        .then((response) => {
          this.migration = response.data;
          this.$closeLoader();
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
    openRollbackDialog() {
      this.getMigration();
      this.willRollbackIndex = [];
      this.rollbackSteps = 0;
      this.rollbackIndex = null;
      this.rollbackDialog = true;
    },
    confirmRollback() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.rollbackMigration.title"),
        text: this.$t("action.rollbackMigration.text"),
        accept: this.rollback,
        acceptText: this.$t("action.rollbackMigration.accept"),
        cancelText: this.$t("action.rollbackMigration.cancel"),
      });
    },
    rollback() {
      this.$v.$touch();
      if (!this.$v.$invalid) {
        this.$openLoader();
        this.$api.badasoDatabase
          .rollback({
            step: this.rollbackSteps,
          })
          .then((response) => {
            this.$closeLoader();

            if (this.isDeleteFile == true) {
              this.$openLoader();
              this.$api.badasoDatabase
                .deleteMigration({
                  file_name: this.willRollbackFile,
                })
                .then((response) => {
                  this.$closeLoader();
                })
                .catch((error) => {
                  this.$closeLoader();
                  this.$vs.notify({
                    title: this.$t("alert.danger"),
                    text: error.message,
                    color: "danger",
                  });
                });
            } else {
              this.getStatusMigration();
            }
            this.getMigration();
            this.getTableList();
            this.$vs.notify({
              title: this.$t("alert.success"),
              text: response.data,
              color: "success",
            });
          })
          .catch((error) => {
            this.$closeLoader();
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          });
        this.rollbackDialog = false;
      }
    },
    setRollbackIndex(data) {
      const flag = this.willRollbackIndex;
      const total = this.migration.length;
      const diff = total - Math.min(...flag);
      const rollbackFileName = [];
      const items = [];

      if (this.rollbackIndex == null) {
        for (let index = total; index > flag; index--) {
          items.push(index);
        }

        for (let index = Math.min(...flag); index < total; index++) {
          rollbackFileName.push(data[index].migration);
        }

        this.rollbackSteps = diff;
        this.willRollbackIndex.push(...items);
        this.rollbackIndex = Math.min(...flag);
        this.willRollbackFile = rollbackFileName;
      } else if (this.rollbackIndex > Math.min(...flag)) {
        for (let index = total; index > Math.min(...flag); index--) {
          items.push(index);
        }

        for (let index = Math.min(...flag); index < total; index++) {
          rollbackFileName.push(data[index].migration);
        }

        this.rollbackSteps = diff;
        this.willRollbackIndex.push(...items);
        this.rollbackIndex = Math.min(...flag);
        this.willRollbackFile = rollbackFileName;
      } else {
        this.willRollbackIndex = [];
        this.rollbackSteps = 0;
        this.rollbackIndex = null;
        this.willRollbackFile = [];
      }
    },
    migrate() {
      this.$openLoader();
      this.$api.badasoDatabase
        .migrate()
        .then((response) => {
          this.$closeLoader();
          this.getTableList();
          this.getStatusMigration();
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: response.message,
            color: "success",
          });
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
    confirmDelete() {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteMigration,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
      });
    },
    deleteMigration() {
      this.$openLoader();
      this.$api.badasoDatabase
        .deleteMigration({
          file_name: this.notMigratedFile,
        })
        .then((response) => {
          this.$closeLoader();
          this.getTableList();
          this.getStatusMigration();
          this.$vs.notify({
            title: this.$t("alert.success"),
            text: response.message,
            color: "success",
          });
        })
        .catch((error) => {
          this.$closeLoader();
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
        });
      this.getTableList();
      this.getStatusMigration();
    },
  },
};
</script>
