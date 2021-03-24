<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
      <vs-col vs-lg="4">
        <div style="float: right">
          <vs-button color="primary" type="relief" :to="{ name: 'DatabaseAdd' }"
            v-if="$helper.isAllowed('add_database')"
            ><vs-icon icon="add"></vs-icon> {{ $t('database.browse.addButton') }}</vs-button
          >
          <vs-button color="success" type="relief" @click="openRollbackDialog()"
            v-if="$helper.isAllowed('rollback_database')"
            ><vs-icon icon="refresh"></vs-icon> {{ $t('database.browse.rollbackButton') }}</vs-button
          >
        </div>
      </vs-col>
    </vs-row>

    <vs-popup
      :title="$t('database.browse.warning.title')"
      :active.sync="isNotMigrated"
      @close="isNotMigrated = true"
      style="z-index: 26000"
      button-close-hidden
    >
      <p>{{ $t('database.browse.warning.notAllowed') }}</p>
      <p v-for="(data,index) in notMigratedFile" :key="index">{{ data }}</p>

      <vs-divider class="mt-4"></vs-divider>

      <div style="float: right">
        <vs-button color="warning" type="relief" @click="goBack()"
          ><vs-icon icon="chevron_left"></vs-icon> {{ $t('database.browse.goBackButton') }}</vs-button
        >
        <vs-button color="danger" type="relief" @click="deleteMigration()"
          v-if="$helper.isAllowed('delete_migration')"
          ><vs-icon icon="delete"></vs-icon> {{ $t('database.browse.deleteMigrationButton') }}</vs-button
        >
        <vs-button color="success" type="relief" @click="migrate()"
          v-if="$helper.isAllowed('migrate_database')"
          ><vs-icon icon="arrow_upward"></vs-icon> {{ $t('database.browse.migrateButton') }}</vs-button
        >
      </div>
    </vs-popup>

    <vs-popup @cancel="rollbackDialog = false" @accept="rollback" :active.sync="rollbackDialog" :accept-text="$t('action.delete.accept')" :cancel-text="$t('action.delete.cancel')" :title="$t('database.rollback.title')" color="success" style="z-index: 26000">
      <vs-row>
        <vs-table :data="migration">
        <template slot="thead">
          <vs-th sort-key="migration"> {{ $t('database.migration.header.migration') }} </vs-th>
          <vs-th> {{ $t('crud.header.action') }} </vs-th>
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
              <vs-checkbox v-model="willRollbackIndex" :vs-value="index" @change="setRollbackIndex(data)" :disabled="disableCheckbox(index)"></vs-checkbox>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      </vs-row>
      <vs-row>
        <vs-spacer></vs-spacer>
        <vs-checkbox v-model="isDeleteFile" class="mr-2">Delete Migration File ?</vs-checkbox>
        <vs-button
          color="danger"
          type="relief"
          @click="rollback()"
          v-if="$helper.isAllowed('rollback_database')"
        >
          {{ $t('database.migration.button.rollback') }}
        </vs-button>
      </vs-row>
    </vs-popup>

    <vs-row v-if="$helper.isAllowed('browse_database')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t('database.browse.title') }}</h3>
          </div>
          <div>
            <vs-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="tables"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('database.add.footer.descriptionTitle')"
              :description-connector="$t('database.add.footer.descriptionConnector')"
              :description-body="$t('database.add.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="tableName"> {{ $t('crud.header.table') }} </vs-th>
                <vs-th> {{ $t('crud.header.action') }} </vs-th>
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
                  <vs-td style="width: 1%; white-space: nowrap">
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.stop
                      v-if="$helper.isAllowed('edit_database')"
                      :to="{
                        name: 'DatabaseAlter',
                        params: { tableName: data[index].tableName },
                      }"
                      >{{ $t('database.browse.alterButton') }}</vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click="openConfirm(data[index].tableName)"
                      v-if="$helper.isAllowed('delete_database')"
                      >{{ $t('database.browse.dropButton') }}</vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
    <vs-row v-else>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <h3>{{ $t('crud.warning.notAllowed') }}</h3>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb.vue";
export default {
  components: { BadasoBreadcrumb },
  name: "Browse",
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
    isDeleteFile: false
  }),
  mounted() {
    this.getStatusMigration();
    this.getTableList();
    process.env.NODE_ENV === 'development' ? this.isDeleteFile = false : this.isDeleteFile = true
  },
  methods: {
    goBack() {
      this.$router.back()
    },
    disableCheckbox(index) {
      return this.willRollbackIndex.includes(index-1)
    },
    getStatusMigration() {
      this.$api.database
        .check()
        .then((response) => {
          this.$vs.loading.close();
          this.isNotMigrated = response.data.notMigrated
          this.notMigratedFile = response.data.data
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
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
        title: this.$t('action.delete.title'),
        text: this.$t('action.delete.text'),
        accept: this.deleteDatabase,
        acceptText: this.$t('action.delete.accept'),
        cancelText: this.$t('action.delete.cancel'),
        cancel: () => {
          this.tableName = null;
        },
      });
    },
    getTableList() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.crud
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.tables = response.data.tablesWithCrudData;
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteDatabase() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.database
        .delete({
          table: this.tableName,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getTableList();
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    },
    getMigration() {
      this.$vs.loading({
        type: "sound",
      });

      this.$api.database
        .browseMigration()
        .then((response) => {
          this.migration = response.data;
          this.$vs.loading.close();
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    },
    openRollbackDialog() {
      this.getMigration()
      this.willRollbackIndex = []
      this.rollbackSteps = 0
      this.rollbackIndex = null
      this.rollbackDialog = true
    },
    rollback() {
      this.$vs.loading({
        type: "sound",
      });
      this.$api.database
        .rollback({
          step: this.rollbackSteps,
        })
        .then((response) => {
          this.$vs.loading.close();
          
          if (this.isDeleteFile == true) {
            this.$api.database
              .deleteMigration({
                file_name: this.willRollbackFile
              })
              .then((response) => {
                this.$vs.loading.close();
              })
              .catch((error) => {
                this.$vs.loading.close();
                this.$vs.notify({
                  title: this.$t('alert.danger'),
                  text: error.message,
                  color: "danger",
                });
              });
          }

          this.getMigration();
          this.getTableList();
          this.$vs.notify({
            title: this.$t('alert.success'),
            text: response.data,
            color: "success",
          });
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
      this.rollbackDialog = false
    },
    setRollbackIndex(data) {
      let flag = this.willRollbackIndex;
      let total = this.migration.length;
      let diff = total - Math.min(...flag);
      let rollbackFileName = [];
      let items = [];

      if (this.rollbackIndex === null) {
        for (let index = total; index > flag; index--) {
          items.push(index) 
        }

        for (let index = Math.min(...flag); index < total; index++) {
          rollbackFileName.push(data[index].migration)
        }

        this.rollbackSteps = diff
        this.willRollbackIndex.push(...items);
        this.rollbackIndex = Math.min(...flag);
        this.willRollbackFile = rollbackFileName;
      } else if (this.rollbackIndex > Math.min(...flag)) {
        for (let index = total; index > Math.min(...flag); index--) {
          items.push(index) 
        }

        for (let index = Math.min(...flag); index < total; index++) {
          rollbackFileName.push(data[index].migration)
        }

        this.rollbackSteps = diff
        this.willRollbackIndex.push(...items);
        this.rollbackIndex = Math.min(...flag)
        this.willRollbackFile = rollbackFileName;
      } else {
        this.willRollbackIndex = []
        this.rollbackSteps = 0
        this.rollbackIndex = null
        this.willRollbackFile = []
      }
    },
    migrate() {
      this.$api.database
        .migrate()
        .then((response) => {
          this.$vs.loading.close();
          this.getTableList();
          this.getStatusMigration();
          this.$vs.notify({
            title: this.$t('alert.success'),
            text: response.message,
            color: "success",
          });
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    },
    deleteMigration() {
      this.$api.database
        .deleteMigration({
          file_name: this.notMigratedFile
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getTableList();
          this.getStatusMigration();
          this.$vs.notify({
            title: this.$t('alert.success'),
            text: response.message,
            color: "success",
          });
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: this.$t('alert.danger'),
            text: error.message,
            color: "danger",
          });
        });
    }
  },
};
</script>
