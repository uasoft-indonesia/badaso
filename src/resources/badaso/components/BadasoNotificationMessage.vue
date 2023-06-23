<template>
  <div class="top-navbar__notification">
    <a
      v-on:click="openOrCloseSideBarNotification()"
      href="#"
      :style="{ color: topbarFontColor }"
    >
      <vs-icon icon="notifications"></vs-icon>
      <sup>{{ countUnreadMessage }}</sup>
    </a>

    <!-- list notification -->
    <vs-sidebar
      position-right
      parent="body"
      default-index="1"
      color="primary"
      class="sidebarx"
      spacer
      v-model="sideBarNotification"
    >
      <div index="1" icon="notifications" slot="header">
        <vs-sidebar-item
          index="0"
          class="top-navbar__notification-item"
          icon="notifications"
        >
          <strong>{{ $t("notification.notification") }}</strong>
        </vs-sidebar-item>
      </div>
      <vs-sidebar-item
        icon="question_answer"
        v-for="(message, index) in messages"
        :index="index"
        :key="index"
        :style="message.style"
      >
        <div
          v-on:click="openSideBarDetailMessage(message, index)"
          class="notification-item"
        >
          <h5>{{ message.title }}</h5>
          <span
            v-html="
              message.content.length > 20
                ? message.content.substring(0, 20) + '...'
                : message.content
            "
          >
          </span>
          <vs-row style="align-items: center">
            <vs-icon
              icon="schedule"
              :color="topbarFontColor"
              style="margin-right: 5px"
            ></vs-icon>
            <p>{{ message.createdAt }}</p>
          </vs-row>
        </div>
      </vs-sidebar-item>
    </vs-sidebar>

    <!-- detail message notification -->
    <vs-sidebar
      position-right
      parent="body"
      default-index="1"
      color="primary"
      class="sidebarx"
      spacer
      v-model="sideBarDetailMessage"
    >
      <div class="header-sidebar" index="1" icon="notifications" slot="header">
        <vs-sidebar-item
          index="1"
          v-on:click="closeSideBarDetailMessage()"
          icon="chevron_left"
        >
          <h4>{{ $t("notification.detailMessage") }}</h4>
        </vs-sidebar-item>
      </div>
      <vs-row>
        <div class="m-3" style="margin-left: 14px; margin-right: 14px">
          <h5>{{ detailMessage.title }}</h5>
          <span v-html="detailMessage.content" class="mt-2"></span>
          <vs-divider></vs-divider>

          <div>
            <vs-row class="row-div">
              <vs-icon
                class="m-1"
                icon="person"
                :color="topbarFontColor"
              ></vs-icon>
              <span>{{ detailSenderMessage.name }}</span>
            </vs-row>
          </div>

          <div>
            <vs-row class="row-div">
              <vs-icon
                class="m-1"
                icon="schedule"
                :color="topbarFontColor"
              ></vs-icon>
              <span>{{ detailMessage.createdAt }}</span>
            </vs-row>
          </div>
        </div>
      </vs-row>
    </vs-sidebar>
  </div>
</template>

<script>
import moment from "moment";

export default {
  data() {
    return {
      sideBarNotification: false,
      sideBarDetailMessage: false,
      messages: [],
      detailMessage: {},
      detailSenderMessage: {},
    };
  },
  props: {
    topbarFontColor: {
      type: String,
      default: "#06bbd3",
    },
  },
  methods: {
    openSideBarDetailMessage(message, index) {
      this.sideBarDetailMessage = true;
      this.sideBarNotification = false;
      this.detailMessage = message;
      this.detailSenderMessage = message.senderUsers;
      if (!message.isRead) {
        this.messages[index].isRead = 1;
        this.messages[index].style = { backgroundColor: "#ffffff" };
        this.readMessage(message.id);
        this.loadUnreadMessage();
      }
    },
    closeSideBarDetailMessage() {
      this.sideBarDetailMessage = false;
      this.sideBarNotification = true;
    },
    loadUnreadMessage() {
      this.$store.commit("badaso/SET_GLOBAL_STATE", {
        key: "countUnreadMessage",
        value: this.messages.filter((message) => message.isRead != 1).length,
      });
    },
    getMessages() {
      this.$api.badasoFcm
        .getMessages()
        .then(({ data }) => {
          this.messages = data.messages.map((item, index) => {
            item.style = {
              backgroundColor: !item.isRead ? "#f0f5f9" : "#ffffff",
            };
            if (item.createdAt) {
              item.createdAt = moment(item.createdAt)
                .utc()
                .format("YYYY-MM-DD HH:mm:ss");
            }
            return item;
          });

          this.loadUnreadMessage();
        })
        .catch((error) => {
          if (error.status == 401) {
            this.$vs.notify({
              title: this.$t("alert.error"),
              text: error.message,
              color: "danger",
            });
          } else {
            this.$vs.notify({
              title: this.$t("alert.danger"),
              text: error.message,
              color: "danger",
            });
          }
        });
    },
    readMessage(id) {
      this.$api.badasoFcm.readMessage(id).catch((error) => {
        this.$vs.notify({
          title: this.$t("alert.danger"),
          text: error.message,
          color: "danger",
        });
      });
    },
    openOrCloseSideBarNotification() {
      this.sideBarNotification = !this.sideBarNotification;
      this.getMessages();
    },
  },
  computed: {
    countUnreadMessage() {
      const countUnreadMessage =
        this.$store.getters["badaso/getGlobalState"].countUnreadMessage;
      return countUnreadMessage;
    },
  },
  created() {
    this.getMessages();
  },
};
</script>
