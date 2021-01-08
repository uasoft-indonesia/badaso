<template>
  <div>
    <vs-row>
      <vs-col vs-lg="8">
        <badaso-breadcrumb></badaso-breadcrumb>
      </vs-col>
    </vs-row>
    <vs-row v-if="$helper.isAllowed('edit_users')">
      <vs-col vs-lg="12">
        <vs-card>
          <div slot="header">
            <h3>Edit User</h3>
          </div>
          <vs-row>
            <badaso-text
              v-model="user.name"
              size="12"
              label="Name"
              placeholder="Name"
            ></badaso-text>
            <badaso-text
              v-model="user.email"
              size="6"
              label="Email"
              placeholder="Email"
            ></badaso-text>
            <badaso-password
              v-model="user.password"
              size="6"
              label="Password"
              placeholder="Leave blank if unchanged"
            ></badaso-password>
            <badaso-upload-image
              v-model="user.avatar"
              size="12"
              label="New Avatar"
              placeholder="New Avatar"
            ></badaso-upload-image>
            <vs-col vs-lg="12" class="mb-3">
              <badaso-code-editor
                v-model="user.additionalInfo"
                size="12"
                label="Additional Info (JSON)"
                placeholder="Additional Info (JSON)"
              ></badaso-code-editor>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
      <vs-col vs-lg="12">
        <vs-card>
          <vs-row>
            <vs-col vs-lg="12">
              <vs-button color="primary" type="relief" @click="submitForm">
                <vs-icon icon="save"></vs-icon> Save
              </vs-button>
            </vs-col>
          </vs-row>
        </vs-card>
      </vs-col>
    </vs-row>
  </div>
</template>
<script>
import BadasoText from "../../components/BadasoText";
import BadasoBreadcrumb from "../../components/BadasoBreadcrumb";
import BadasoPassword from "../../components/BadasoPassword.vue";
import BadasoCodeEditor from "../../components/BadasoCodeEditor.vue";
import BadasoUploadImage from "../../components/BadasoUploadImage.vue";

export default {
  name: "Browse",
  components: {
    BadasoText,
    BadasoBreadcrumb,
    BadasoCodeEditor,
    BadasoPassword,
    BadasoUploadImage,
  },
  data: () => ({
    user: {
      email: "",
      name: "",
      avatar: {},
      password: "",
      additionalInfo: "",
    },
  }),
  computed: {
    loggedInUser: {
      get() {
        let user = this.$store.getters.getUser;
        return user;
      },
    },
  },
  mounted() {
    this.getUserDetail()
  },
  methods: {
    getUserDetail() {
        this.$vs.loading({
        type: "sound",
      });
      this.$api.user
        .read({
            id: this.$route.params.id
        })
        .then((response) => {
          this.$vs.loading.close();
          this.user = response.data.user;
          this.user.password = '';
          this.user.additionalInfo = this.user.additionalInfo ? JSON.parse(this.user.additionalInfo) : '';
        })
        .catch((error) => {
          this.$vs.loading.close();
          this.$vs.notify({
            title: "Danger",
            text: error.message,
            color: "danger",
          });
        });
    },
    submitForm() {
      this.$vs.loading();
      this.$api.user
        .edit({
          id: this.$route.params.id,
          email: this.user.email,
          name: this.user.name,
          avatar: this.user.avatar.base64,
          password: this.user.password,
          additionalInfo: JSON.stringify(this.user.additionalInfo),
        })
        .then((response) => {
          if (this.loggedInUser.id === this.user.id) {
            this.$store.commit("FETCH_USER");
          }
          this.$vs.loading.close();
          this.$router.push({ name: "UserBrowse" });
        })
        .catch((error) => {
          this.$vs.loading.close();
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
