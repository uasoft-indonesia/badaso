<template>
  <div class="d-flex align-items-center mb-2 preview mr-4">
    <a
      class="text-white-dark"
      v-on:click="openOrCloseSideBarNotification()"
      href="#"
      :style="{ color: topbarFontColor }"
      ><vs-icon icon="notifications"></vs-icon
    ></a>

    <vs-sidebar
      position-right
      parent="body"
      default-index="1"
      color="primary"
      class="sidebarx"
      spacer
      v-model="sideBarNotification"
    >
      <div class="header-sidebar" index="1" icon="notifications" slot="header">
        <vs-sidebar-item index="0" icon="notifications">
          <h4>{{$t('notification.notification')}}</h4>
        </vs-sidebar-item>
      </div>
      <vs-sidebar-item
        icon="question_answer"
        v-for="(message, index) in messages"
        :index="index"
        :key="index"
        :style="notifStyles(message)"
      >
        <div
          v-on:click="openSideBarDetailMessage(message, index)"
          class="notification-item"
        >
          <h5>{{ message.title }}</h5>
          <p>
            {{
              message.content.lenght > 100
                ? message.content.substring(0, 100) + "..."
                : message.content
            }}
          </p>

          <vs-row>
            <vs-icon icon="schedule" :color="topbarFontColor"></vs-icon>
            <p>{{ message.createdAt }}</p>
          </vs-row>
        </div>
      </vs-sidebar-item>
    </vs-sidebar>

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
          <h4>{{$t('notification.detailMessage')}}</h4>
        </vs-sidebar-item>
      </div>
      <vs-row>
        <div class="m-3">
          <h5>{{ detailMessage.title }}</h5>
          <p class="mt-2">{{ detailMessage.content }}</p>

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

<style>
.notification-item {
  display: flex;
  flex-direction: column;
}
.row-div {
  display: flex;
  align-items: center;
}
</style>

<script>
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
      let messages = this.messages
      this.sideBarDetailMessage = true;
      this.sideBarNotification = false;
      this.detailMessage = message;
      this.detailSenderMessage = message.senderUsers;
      if (!message.isRead) {
        messages[index].isRead = 1
        this.messages = messages
        this.readMessage(message.id)
      }
    },
    closeSideBarDetailMessage() {
      this.sideBarDetailMessage = false;
      this.sideBarNotification = true;
    },
    getMessages() {
      this.$api.badasoFcm
        .getMessages()
        .then(({ data }) => {
          this.messages = data.messages;
        })
        .catch((error) => {
          this.$vs.notify({
            title: this.$t("alert.danger"),
            text: error.message,
            color: "danger",
          });
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
    notifStyles(message) {
      return { backgroundColor: message.isRead == 0 ? "#f0f5f9" : "#fffff" };
    },
  },
  mounted() {
    this.getMessages();
  },
};
</script>
