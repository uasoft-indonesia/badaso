<template>
  <div>
    <badaso-breadcrumb-row>
      <template slot="action"> </template>
    </badaso-breadcrumb-row>
    <vs-row>
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Notification Inbox</h3>
          </div>
          <div>
            <badaso-table
              v-model="selected"
              pagination
              max-items="10"
              search
              :data="dataNotification"
              stripe
              description
              :description-items="descriptionItems"
              :description-title="$t('role.footer.descriptionTitle')"
              :description-connector="$t('role.footer.descriptionConnector')"
              :description-body="$t('role.footer.descriptionBody')"
            >
              <template slot="thead">
                <vs-th :sort-key="title">
                  {{ $t("notification.table.thead.title") }}
                </vs-th>
                <vs-th :sort-key="body">
                  {{ $t("notification.table.thead.message") }}
                </vs-th>
                <vs-th :sort-key="user_name">
                  {{ $t("notification.table.thead.user") }}
                </vs-th>
                <vs-th> {{ $t("crud.header.action") }} </vs-th>
              </template>

              <template slot-scope="{ data }">
                <vs-tr
                  v-for="(notif, indexMessage) in data"
                  :key="indexMessage"
                  :data="notif"
                >
                  <vs-td :data="dataNotification[indexMessage].title">
                    {{ dataNotification[indexMessage].title }}
                  </vs-td>
                  <vs-td :data="dataNotification[indexMessage].body">
                    {{ dataNotification[indexMessage].body }}
                  </vs-td>
                  <vs-td :data="dataNotification[indexMessage].user_name">
                    {{ dataNotification[indexMessage].user_name }}
                  </vs-td>
                  <vs-td>
                    <badaso-dropdown vs-trigger-click>
                      <vs-button
                        size="large"
                        type="flat"
                        icon="more_vert"
                      ></vs-button>
                      <vs-dropdown-menu>
                        <badaso-dropdown-item
                          icon="delete"
                          @click="confirmDelete(indexMessage)"
                        >
                          Delete
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
  </div>
</template>

<script>
import { keyMessageNotification } from "../../utils/firebase";

export default {
  name: "NotificationBrowse",
  components: {},
  data() {
    return {
      descriptionItems: [10, 50, 100],
      selected: [],
      dataNotification: [],
    };
  },
  created() {
    this.dataNotification = this.getNotificationMessage();
  },
  methods: {
    confirmDelete(indexMessage) {
      this.$vs.dialog({
        type: "confirm",
        color: "danger",
        title: this.$t("action.delete.title"),
        text: this.$t("action.delete.text"),
        accept: () => this.deleteNotificationMessage(indexMessage),
        acceptText: this.$t("action.delete.accept"),
        cancelText: this.$t("action.delete.cancel"),
        cancel: () => {},
      });
    },
    deleteNotificationMessage(indexMessage) {
      const dataMessageFromLocalStorage = this.getNotificationMessage().filter(
        (item, index) => index != indexMessage
      );
      localStorage.setItem(
        keyMessageNotification,
        JSON.stringify(dataMessageFromLocalStorage)
      );
      this.dataNotification = this.getNotificationMessage();
    },
    getNotificationMessage() {
      const dataMessageFromLocalStorage = localStorage.getItem(
        keyMessageNotification
      );
      return dataMessageFromLocalStorage != null
        ? JSON.parse(dataMessageFromLocalStorage)
        : [];
    },
  },
};
</script>
