<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action">
        <vs-button
          color="primary"
          type="relief"
          :to="{ name: 'UserManagementAdd' }"
          v-if="$helper.isAllowed('add_users')"
          ><vs-icon icon="add"></vs-icon> {{ $t("action.add") }}</vs-button
        >
        <vs-button
          color="danger"
          type="relief"
          v-if="selected.length > 0 && $helper.isAllowed('delete_users')"
          @click.stop
          @click="confirmDeleteMultiple"
          ><vs-icon icon="delete_sweep"></vs-icon>
          {{ $t("action.bulkDelete") }}</vs-button
        >
      </template>
    </badaso-breadcrumb-row>
    <vs-row v-if="$helper.isAllowed('browse_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>{{ $t("user.title") }}</h3>
          </div>
          <div>
            <vs-table
              multiple
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="users"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('user.footer.descriptionTitle')"
              :description-connector="$t('user.footer.descriptionConnector')"
              :description-body="$t('user.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th sort-key="name"> {{ $t("user.header.name") }} </vs-th>
                <vs-th sort-key="email"> {{ $t("user.header.email") }} </vs-th>
                <vs-th> {{ $t("user.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr :data="tr" :key="indextr" v-for="(tr, indextr) in data">
                  <vs-td :data="data[indextr].name">
                    {{ data[indextr].name }}
                  </vs-td>
                  <vs-td :data="data[indextr].email">
                    {{ data[indextr].email }}
                  </vs-td>
                  <vs-td style="width: 1%; white-space: nowrap">
                    <vs-button
                      color="success"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'UserManagementRead',
                        params: { id: data[indextr].id },
                      }"
                      v-if="$helper.isAllowed('read_users')"
                      ><vs-icon icon="visibility"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="primary"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'UserManagementRoles',
                        params: { id: data[indextr].id },
                      }"
                      v-if="$helper.isAllowed('browse_user_role')"
                      ><vs-icon icon="list"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="warning"
                      type="relief"
                      @click.stop
                      :to="{
                        name: 'UserManagementEdit',
                        params: { id: data[indextr].id },
                      }"
                      v-if="$helper.isAllowed('edit_users')"
                      ><vs-icon icon="edit"></vs-icon
                    ></vs-button>
                    <vs-button
                      color="danger"
                      type="relief"
                      @click.stop
                      @click="confirmDelete(data[indextr].id)"
                      v-if="$helper.isAllowed('delete_users')"
                      ><vs-icon icon="delete"></vs-icon
                    ></vs-button>
                  </vs-td>
                </vs-tr>
              </template>
            </vs-table>
          </div>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>

<script>
export default {
  name: "UserManagementBrowse",
  components: {
  },
  data: () => ({
    selected: [],
    descriptionItems: [10, 50, 100],
    users: [],
    willDeleteId: null,
  }),
  mounted() {
    this.getUserList();
  },
  methods: {
    confirmDelete(id) {
      this.willDeleteId = id;
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.deleteUser,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {
          this.willDeleteId = null;
        },
      });
    },
    confirmDeleteMultiple(id) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: this.bulkDeleteUser,
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    getUserList() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.user
        .browse()
        .then((response) => {
          this.$vs.loading.close();
          this.selected = [];
          this.users = response.data.users;
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
    deleteUser() {
      this.$vs.loading(this.$loadingConfig);
      this.$api.user
        .delete({
          id: this.willDeleteId,
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getUserList();
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
    bulkDeleteUser() {
      const ids = this.selected.map((item) => item.id);
      this.$vs.loading(this.$loadingConfig);
      this.$api.user
        .deleteMultiple({
          ids: ids.join(","),
        })
        .then((response) => {
          this.$vs.loading.close();
          this.getUserList();
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
  },
};
</script>
