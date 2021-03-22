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

    <vs-popup @cancel="rollbackDialog = false" @accept="rollback" :active.sync="rollbackDialog" :accept-text="$t('action.delete.accept')" :cancel-text="$t('action.delete.cancel')" :title="$t('database.rollback.title')" color="success">
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
              <vs-checkbox v-model="willRollbackIndex" :vs-value="index" @change="setRollbackIndex()" :disabled="disableCheckbox(index)"></vs-checkbox>
            </vs-td>
          </vs-tr>
        </template>
      </vs-table>
      <vs-row>
        <vs-spacer></vs-spacer>
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
    rollbackIndex: null
  }),
  mounted() {
    this.getTableList();
  },
  methods: {
    disableCheckbox(index) {
      return this.willRollbackIndex.includes(index-1)
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
          this.$store.commit("FETCH_MENU");
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
          this.rollbackDialog = true
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
          this.getMigration()
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
    },
    setRollbackIndex() {
      let flag = this.willRollbackIndex;
      let total = this.migration.length;
      let diff = total - Math.min(...flag);
      let items = [];

      if (this.rollbackIndex === null) {
        for (let index = total; index > flag; index--) {
          items.push(index) 
        }

        this.rollbackSteps = diff
        this.willRollbackIndex.push(...items);
        this.rollbackIndex = Math.min(...flag)
      } else if (this.rollbackIndex > Math.min(...flag)) {
        for (let index = total; index > Math.min(...flag); index--) {
          items.push(index) 
        }
        this.rollbackSteps = diff
        this.willRollbackIndex.push(...items);
        this.rollbackIndex = Math.min(...flag)
      } else {
        this.willRollbackIndex = []
        this.rollbackSteps = 0
        this.rollbackIndex = null
      }
    }
  },
};
</script>
